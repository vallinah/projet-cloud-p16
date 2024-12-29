using Aspnet.Models;
using Aspnet.Services;
using AspNet.Models;
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
            if (request.Login == null || request.Password == null)
            {
                return Unauthorized("L'email et le mot de passe sont obligatoire");

            }
            // Vérifier les identifiants de l'administrateur
            var users = await _usersService.GetUsersByEmailAsync(request.Login);

            if (users == null)
            {
                return Unauthorized("L'email est incorrecte.");
            }
            if (!users.IsValid)
            {
                return Unauthorized("Le compte est desactive.");
            }
            else
            {
                var hasExceptionFound = await _sessionConfigService.MaxTentativesAsync(users.UserId);
                if (hasExceptionFound)
                {
                    bool deactivate = await _usersService.DeactivateAccount(users.UserId);
                    return Unauthorized("Vous avez atteint la limite de tentative de connexion");
                }
                Users? user = await _usersService.AuthenticateUsersAsync(users.Email, request.Password);
                if (user == null)
                {
                    await _sessionConfigService.IncrementTentativeAsync(users.UserId, users.Email);
                    return Unauthorized("Le mot de passe est incorrecte");
                }
                // Générer un code PIN et l'envoyer par email
                var pinCode = _pinCodeService.GeneratePinCode();
                _emailPinsService.SendPinCodeEmail(user.Email, pinCode);
                EmailPins emailPins = new EmailPins();
                emailPins.UserId = users.UserId;
                DateTime now = DateTime.UtcNow;
                DateTime newDate = now.AddSeconds(90);
                emailPins.CreatedDate = now;
                emailPins.ExpirationDate = newDate;
                emailPins.CodePin = pinCode;
                await _emailPinsService.AddEmailPinsAsync(emailPins);
                return Ok("Authentification réussie. Un code PIN a été envoyé à votre email.");
            }
        }

        [HttpPost("check_pin_code")]
        public async Task<IActionResult> CheckPinCode([FromBody] PinCodeRequest request)
        {
            DateTime now = DateTime.UtcNow;
            bool check = await _emailPinsService.CheckPinCode(request.UserId, request.PinCode, now);
            Users? users = await _usersService.GetUserByIdAsync(request.UserId);
            if (users == null)
            {
                return NotFound("L'users n'existe pas");
            }
            var hasExceptionFound = await _sessionConfigService.MaxTentativesAsync(users.UserId);
            if (hasExceptionFound)
            {
                bool deactivate = await _usersService.DeactivateAccount(users.UserId);
                return Unauthorized("Vous avez atteint la limite de tentative de connexion");
            }
            if (!check)
            {
                await _sessionConfigService.IncrementTentativeAsync(users.UserId, users.Email);
                return Unauthorized("Vous avez entrer un code pin incorrect.");
            }
            await _sessionConfigService.ResetTentativeAsync(users.UserId);
            await _sessionUserService.InsertSessionAsync(users.UserId, users.UserId, users.UserId);
            return Ok("Votre code PIN est correcte, authentification réussie.");
        }

        [HttpPost("deactivate")]
        public async Task<IActionResult> DeactiveAccount([FromBody] string idUser)
        {
            try
            {
                bool deactivate = await _usersService.DeactivateAccount(idUser);
                if (deactivate)
                {
                    return Ok("Desactivation réussie.");
                }
                return Unauthorized("Desactivation non réussie");
            }
            catch (System.Exception ex)
            {
                return Ok($"Desactivation non réussie.:{ex.Message}");
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
                    return Ok("activation réussie.");
                }
                return Ok($"Activation non réussie.");

            }
            catch (System.Exception ex)
            {
                return Ok($"Activation réussie.:{ex.Message}");
            }
        }

        [HttpPut("update")]
        public async Task<IActionResult> UpdateUsersController([FromBody] Users newUser)
        {
            if (newUser == null)
            {
                return BadRequest("invalide champ obligatoure.");
            }
            var session_users = await _sessionUserService.GetSessionByUserIdAndNameAsync(newUser.UserId, newUser.UserId);
            if (session_users == null)
            {
                return NotFound("L'users n'est pas connecté");
            }
            var resultat = await _usersService.UpdateUser(newUser);

            if (!resultat)
            {
                return StatusCode(500, "Problem nandritra ny requette.");
            }
            // redirigena mankany am URL hafa izy eto 
            return Ok("User updater");
        }

        [HttpGet("update/{idUser}")]
        public async Task<IActionResult> GetUserByIdController(string idUser)
        {
            var session_users = await _sessionUserService.GetSessionByUserIdAndNameAsync(idUser, idUser);
            if (session_users == null)
            {
                return NotFound("L'users n'est pas connecté");
            }
            Users? users = await _usersService.GetUserByIdAsync(idUser);
            if (users == null)
            {
                return NotFound($"Utilisateur non trouve a  l id {idUser}");
            }
            return Ok(users);
        }

        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterRequest request)
        {
            Users? existingUser = await _usersService.GetUsersByEmailAsync(request.Email);
            if (existingUser != null)
            {
                return BadRequest("L'utilisateur avec cette Email est déjà inscrit sur notre site!");
            }

            var pinCode = _pinCodeService.GeneratePinCode();

            var newUser = new Users
            {
                FirstName = request.FirstName,
                LastName = request.LastName,
                Email = request.Email,
                Password = _utilService.HashPassword(request.Password),
                DateOfBirth = request.DateOfBirth,
                IsValid = false
            };

            await _usersService.AddUsersAsync(newUser);

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

            return Ok("Un email de validation a été envoyé avec un code PIN.");
        }

        [HttpPost("validate_incription")]
        public async Task<IActionResult> ValidatePin([FromBody] ValidatePinRequest request)
        {
            // Récupérer l'utilisateur à partir de son email
            var user = await _usersService.GetUsersByEmailAsync(request.Email);
            if (user == null)
            {
                return NotFound("Utilisateur non trouvé.");
            }

            // Vérifier si un code PIN existe pour cet utilisateur
            var emailPin = await _emailPinsService.GetEmailPinsByUserIdAsync(user.UserId);

            if (emailPin == null)
            {
                return BadRequest("Code PIN non trouvé.");
            }

            // Vérifier si le code PIN est expiré
            if (emailPin.ExpirationDate < DateTime.UtcNow)
            {
                return BadRequest("Le code PIN a expiré.");
            }

            // Vérifier si le code PIN fourni par l'utilisateur est correct
            if (emailPin.CodePin != request.PinCode)
            {
                return BadRequest("Code PIN incorrect.");
            }

            // Si le code PIN est correct, valider l'utilisateur
            user.IsValid = true;
            await _usersService.UpdateUser(user);

            // Retourner un message de succès
            return Ok("Votre compte a été validé avec succès.");
        }
    }
}
