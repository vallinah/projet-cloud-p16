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
            var admin = await _adminService.AuthenticateAdminAsync(request.Login, request.Password);

            if (admin == null)
            {
                Console.WriteLine($"tsa misy admin hita oooo");
                return NotFound(new
                {
                    status = "error",
                    message = "Admin non trouvé"
                });
            }

            Console.WriteLine($"valeur: {admin.Login}, {admin.Password}");
            return Ok(new
            {
                status = "success",
                data = new
                {
                    adminId = admin.AdminId,
                    login = admin.Login,
                }
            });
        }
    }
}