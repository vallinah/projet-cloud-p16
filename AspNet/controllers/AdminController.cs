using Aspnet.Models;
using Aspnet.Services;
using AspNet.Models;
using Microsoft.AspNetCore.Mvc;
using System.Threading.Tasks;

namespace Aspnet.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AdminController : ControllerBase
    {
        private readonly AdminService _adminService;
        private readonly EmailService _emailService;
        private readonly PinCodeService _pinCodeService;

        public AdminController(AdminService adminService, EmailService emailService, PinCodeService pinCodeService)
        {
            _adminService = adminService;
            _emailService = emailService;
            _pinCodeService = pinCodeService;
        }

        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            // Vérifier les identifiants de l'administrateur
            var admin = await _adminService.AuthenticateAdminAsync(request.Login, request.Password);

            if (admin == null)
            {
                return Unauthorized("Login ou mot de passe incorrect.");
            }

            // Générer un code PIN et l'envoyer par email
            var pinCode = _pinCodeService.GeneratePinCode();
            _emailService.SendPinCodeEmail(admin.Email, pinCode);

            return Ok("Authentification réussie. Un code PIN a été envoyé à votre email.");
        }
    }
}
