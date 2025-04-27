using Aspnet.Models;
using Aspnet.Services;
using AspNet.Models;
using FirebaseAdmin.Auth;
using Google.Cloud.Firestore;
using Microsoft.AspNetCore.Mvc;
using System.Threading.Tasks;

namespace Aspnet.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsersController : ControllerBase
    {
        private readonly UsersService _usersService;
        private readonly EmailPinsService _emailPinsService;
        private readonly PinCodeService _pinCodeService;
        private readonly SessionConfigService _sessionConfigService;
        private readonly SessionUserService _sessionUserService;
        private readonly UtilService _utilService;

        public UsersController(UsersService usersService, EmailPinsService emailPinsService, PinCodeService pinCodeService, SessionConfigService sessionConfigService, SessionUserService sessionUserService, UsersService userService, UtilService utilService)
        {
            _usersService = usersService;
            _emailPinsService = emailPinsService;
            _pinCodeService = pinCodeService;
            _sessionConfigService = sessionConfigService;
            _sessionUserService = sessionUserService;
            _utilService = utilService;
        }

        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {   
            request.Password = _utilService.HashPassword(request.Password);
            if (request.Login == null || request.Password == null)
            {
                return Unauthorized(new { status = "error", message = "L'email et le mot de passe sont obligatoires." });
            }

            var users = await _usersService.GetUsersByEmailAsync(request.Login);

            if (users == null)
            {
                return Unauthorized(new { status = "error", message = "L'email est incorrect." });
            }

            if (!users.IsValid)
            {
                return Unauthorized(new { status = "error", message = "Votre compte est désactivé.Veuiller reesayer plus tard" });
            }
            else
            {
                var hasExceptionFound = await _sessionConfigService.MaxTentativesAsync(users.UserId);
                if (hasExceptionFound)
                {
                    bool deactivate = await _usersService.DeactivateAccount(users.UserId);
                    return Unauthorized(new { status = "error", message = "Vous avez atteint la limite de tentatives de connexion." });
                }

                Users? user = await _usersService.AuthenticateUsersAsync(users.Email, request.Password);
                if (user == null)
                {
                    await _sessionConfigService.IncrementTentativeAsync(users.UserId, users.Email);
                    return Unauthorized(new { status = "error", message = "Le mot de passe est incorrect." });
                }

                var pinCode = _pinCodeService.GeneratePinCode();
                _emailPinsService.SendPinCodeEmail(user.Email, pinCode);
                EmailPins emailPins = new EmailPins
                {
                    UserId = users.UserId,
                    CreatedDate = DateTime.UtcNow,
                    ExpirationDate = DateTime.UtcNow.AddSeconds(90),
                    CodePin = pinCode
                };
                await _emailPinsService.AddEmailPinsAsync(emailPins);

                return Ok(new { status = "success", message = "Authentification réussie. Un code PIN a été envoyé à votre email." });
            }
        }

        [HttpPost("check_pin_code")]
        public async Task<IActionResult> CheckPinCode([FromBody] PinCodeRequest request)
        {
            DateTime now = DateTime.UtcNow;
            var emailPin = await _emailPinsService.GetEmailPinsByUserIdAsync(request.UserId);
            if (emailPin == null)
            {
                return NotFound(new { status = "error", message = "Aucun code PIN n'a été généré pour cet utilisateur." });
            }

            bool check = await _emailPinsService.CheckPinCode(request.UserId, request.PinCode, now);
            Users? users = await _usersService.GetUserByIdAsync(request.UserId);


            if (users == null)
            {
                return NotFound(new { status = "error", message = "L'utilisateur n'existe pas." });
            }

            var hasExceptionFound = await _sessionConfigService.MaxTentativesAsync(users.UserId);
            if (hasExceptionFound)
            {
                bool deactivate = await _usersService.DeactivateAccount(users.UserId);
                return Unauthorized(new { status = "error", message = "Vous avez atteint la limite de tentative de connexion." });
            }

            if (!check)
            {
                await _sessionConfigService.IncrementTentativeAsync(users.UserId, users.Email);
                return Unauthorized(new { status = "error", message = "Vous avez entré un code PIN incorrect." });
            }
            await _sessionConfigService.ResetTentativeAsync(users.UserId);
            await _sessionUserService.InsertSessionAsync(users.UserId, users.UserId, users.UserId);

            return Ok(new { status = "success", message = "Votre code PIN est correct, authentification réussie." });
        }


        [HttpPost("deactivate")]
        public async Task<IActionResult> DeactiveAccount([FromBody] string idUser)
        {
            try
            {
                bool deactivate = await _usersService.DeactivateAccount(idUser);
                if (deactivate)
                {
                    return Ok(new { status = "success", message = "Désactivation réussie." });
                }
                return Unauthorized(new { status = "error", message = "Désactivation non réussie" });
            }
            catch (System.Exception ex)
            {
                return StatusCode(500, new { status = "error", message = $"Désactivation non réussie : {ex.Message}" });
            }
        }


        [HttpPost("activate")]
        public async Task<IActionResult> ActivateAccount([FromBody] ActivationRequest request)
        {
            try
            {
                bool activate = await _usersService.ActivateAccount(request.UserId, request.Token);
                if (activate)
                {
                    await _sessionConfigService.ResetTentativeAsync(request.UserId);
                    return Ok(new { status = "success", message = "Activation réussie." });
                }
                return Unauthorized(new { status = "error", message = "Activation non réussie." });
            }
            catch (System.Exception ex)
            {
                return StatusCode(500, new { status = "error", message = $"Activation non réussie : {ex.Message}" });
            }
        }


        [HttpPut("update")]
        public async Task<IActionResult> UpdateUsersController([FromBody] Users newUser)
        {
            if (newUser == null)
            {
                return BadRequest(new { status = "error", message = "Champs invalides ou manquants." });
            }

            var session_users = await _sessionUserService.GetSessionByUserIdAndNameAsync(newUser.UserId, newUser.UserId);
            if (session_users == null)
            {
                return NotFound(new { status = "error", message = "L'utilisateur n'est pas connecté." });
            }

            var resultat = await _usersService.UpdateUser(newUser);

            if (!resultat)
            {
                return StatusCode(500, new { status = "error", message = "Problème lors de la mise à jour." });
            }

            return Ok(new { status = "success", message = "Utilisateur mis à jour avec succès." });
        }


        [HttpGet("update/{idUser}")]
        public async Task<IActionResult> GetUserByIdController(string idUser)
        {
            var session_users = await _sessionUserService.GetSessionByUserIdAndNameAsync(idUser, idUser);
            if (session_users == null)
            {
                return NotFound(new { status = "error", message = "L'utilisateur n'est pas connecté." });
            }

            Users? users = await _usersService.GetUserByIdAsync(idUser);
            if (users == null)
            {
                return NotFound(new { status = "error", message = $"Utilisateur non trouvé avec l'ID {idUser}." });
            }

            return Ok(new { status = "success", data = users });
        }
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterRequest request)
        {
            try
            {
                // Vérification de l'existence de l'utilisateur
                Users? existingUser = await _usersService.GetUsersByEmailAsync(request.Email);
                if (existingUser != null)
                {
                    return BadRequest(new { status = "error", message = "L'utilisateur avec cet email est déjà inscrit." });
                }

                // Validation du mot de passe
                if (string.IsNullOrEmpty(request.Password) || request.Password.Length < 6)
                {
                    return BadRequest(new { status = "error", message = "Le mot de passe doit contenir au moins 6 caractères." });
                }

                // Création de l'utilisateur
                var newUser = new Users
                {
                    FirstName = request.FirstName,
                    LastName = request.LastName,
                    Email = request.Email,
                    Password = _utilService.HashPassword(request.Password),
                    DateOfBirth = request.DateOfBirth,
                    CreatedDate = DateTime.UtcNow,
                    IsValid = false
                };

                // Enregistrement synchronisé (local + Firebase)
                var registrationResult = await _usersService.RegisterUserWithFirebaseAsync(newUser, request.Password);
                if (!registrationResult.Success)
                {
                    return BadRequest(new { status = "error", message = registrationResult.Message });
                }

                // Génération et envoi du code PIN
                var pinCode = _pinCodeService.GeneratePinCode();
                DateTime now = DateTime.UtcNow;
                var emailPin = new EmailPins
                {
                    CodePin = pinCode,
                    CreatedDate = now,
                    ExpirationDate = now.AddSeconds(90),
                    UserId = newUser.UserId
                };

                await _emailPinsService.AddEmailPinsAsync(emailPin);
                _emailPinsService.SendPinCodeEmail(request.Email, pinCode);

                return Ok(new { 
                    status = "success", 
                    message = "Un email de validation a été envoyé avec un code PIN.",
                    userId = newUser.UserId 
                });
            }
            catch (Exception ex)
            {
                return BadRequest(new { 
                    status = "error", 
                    message = "Une erreur est survenue lors de l'inscription.", 
                    error = ex.Message 
                });
            }
        }
        [HttpPost("validate_inscription")]
        public async Task<IActionResult> ValidatePin([FromBody] ValidatePinRequest request)
        {
            var user = await _usersService.GetUsersByEmailAsync(request.Email);
            if (user == null)
            {
                return NotFound(new { status = "error", message = "Utilisateur non trouvé." });
            }

            var emailPin = await _emailPinsService.GetEmailPinsByUserIdAsync(user.UserId);
            if (emailPin == null)
            {
                return BadRequest(new { status = "error", message = "Code PIN non trouvé." });
            }

            if (emailPin.ExpirationDate < DateTime.UtcNow)
            {
                return BadRequest(new { status = "error", message = "Le code PIN a expiré." });
            }

            if (emailPin.CodePin != request.PinCode)
            {
                return BadRequest(new { status = "error", message = "Code PIN incorrect." });
            }
            user.IsValid = true;
            await _usersService.UpdateUser(user);

            FirestoreDb db = FirestoreDb.Create("projet-cloud-final");
            DocumentReference docRef = db.Collection("users").Document(user.UserId);
            await docRef.UpdateAsync("IsValid", true);

            return Ok(new { status = "success", message = "Votre compte a été validé avec succès.", userId = user.UserId });
        }


        [HttpGet("get-by-email")]
        public async Task<IActionResult> GetUserByEmail([FromQuery] string email)
        {
            try
            {
            if (string.IsNullOrEmpty(email))
            {
                return BadRequest(new
                {
                    status = "error",
                    message = "L'email est requis"
                });
            }

            var user = await _usersService.GetUsersByEmailAsync(email);

            if (user == null)
            {
                return NotFound(new
                {
                    status = "error",
                    message = "Utilisateur non trouvé"
                });
            }

            return Ok(new
            {
                status = "success",
                data = new
                {
                    userId = user.UserId,
                    email = user.Email,
                }
            });
        }
        catch (Exception)
            {
            return StatusCode(500, new
            {
                status = "error",
                message = "Une erreur est survenue lors de la récupération de l'utilisateur"
            });
        }
    }
    }
}
