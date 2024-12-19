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
        private readonly EmailPinsService _emailPinsService;
        private readonly PinCodeService _pinCodeService;

        public AdminController(AdminService adminService, EmailPinsService emailPinsService, PinCodeService pinCodeService)
        {
            _adminService = adminService;
            _emailPinsService = emailPinsService;
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
            return Ok("Authentification réussie.");
        }
    }
}
