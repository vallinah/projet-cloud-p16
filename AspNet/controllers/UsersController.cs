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

        public UsersController(UsersService usersService, EmailPinsService emailPinsService, PinCodeService pinCodeService)
        {
            _usersService = usersService;
            _emailPinsService = emailPinsService;
            _pinCodeService = pinCodeService;
        }

        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            // Vérifier les identifiants de l'administrateur
            var users = await _usersService.AuthenticateUsersAsync(request.Login, request.Password);

            if (users == null)
            {
                return Unauthorized("Login ou mot de passe incorrect.");
            }

            // Générer un code PIN et l'envoyer par email
            var pinCode = _pinCodeService.GeneratePinCode();
            // _emailPinsService.SendPinCodeEmail(admin.Email, pinCode);
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

        [HttpPost("check_pin_code")]
        public async Task<IActionResult> CheckPinCode([FromBody] PinCodeRequest request)
        {
            DateTime now = DateTime.UtcNow;
            bool check = await _emailPinsService.CheckPinCode(request.UserId, request.PinCode, now);

            if (!check)
            {
                return Unauthorized("Vous avez entrer un code pin incorrect.");
            }
            return Ok("Votre code PIN est correcte, authentification réussie.");
        }

        [HttpPost()]
        public async Task<IActionResult> AddUsers([FromBody] Users user)
        {
            try
            {
                user.CreatedDate = DateTime.UtcNow;
                await _usersService.AddUsersAsync(user);
                return Ok("Insertion d'utilisateur réussie.");
            }
            catch (System.Exception ex)
            {
                return Ok($"L'insertion du nouveau utilisateur a echoue:{ex.Message}");
            }
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
                    return Ok("activation réussie.");
                }
                return Ok($"Activation non réussie.");

            }
            catch (System.Exception ex)
            {
                return Ok($"Activation réussie.:{ex.Message}");
            }
        }
    }
}
