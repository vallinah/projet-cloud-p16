using Aspnet.Models;
using Aspnet.Services;
using Microsoft.AspNetCore.Mvc;
using System.Threading.Tasks;

namespace Aspnet.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class SessionConfigController : ControllerBase
    {
        private readonly SessionConfigService _sessionConfigService;

        public SessionConfigController(SessionConfigService sessionConfigService)
        {
            _sessionConfigService = sessionConfigService;
        }

        [HttpGet("GetTentativeMax")]
        public async Task<IActionResult> GetTentativeMax()
        {
            var tentativeMax = await _sessionConfigService.GetTentativeMaxAsync();
            return Ok(new { tentativeMax });
        }

        [HttpPost("SetTentativeMax")]
        public async Task<IActionResult> SetTentativeMax([FromBody] TentativeMaxRequest request)
        {
            if (request == null || request.TentativeMax <= 0)
            {
                return BadRequest("TentativeMax doit être un entier positif.");
            }

            bool success = await _sessionConfigService.SetTentativeMaxAsync(request.TentativeMax);
            if (!success)
            {
                return BadRequest("Échec de la mise à jour de TentativeMax.");
            }

            return Ok(new
            {
                message = "TentativeMax mise à jour avec succès.",
                tentativeMax = request.TentativeMax
            });
        }


        [HttpGet("GetSessionTimeout")]
        public async Task<IActionResult> GetSessionTimeout()
        {
            var timeoutInMinutes = await _sessionConfigService.GetMinuteTimeoutAsync();
            return Ok(new { timeoutInMinutes });
        }

        [HttpPost("SetSessionTimeout")]
        public async Task<IActionResult> SetSessionTimeout([FromBody] SessionTimeoutRequest request)
        {
            if (request == null || request.TimeoutInMinutes <= 0)
            {
                return BadRequest("La durée de la session doit être un nombre positif.");
            }
            bool success = await _sessionConfigService.SetMinuteTimeoutAsync(request.TimeoutInMinutes);
            if (!success)
            {
                return BadRequest("Échec de la mise à jour de la durée de session.");
            }
            return Ok(new
            {
                message = "Durée de session mise à jour avec succès.",
                timeoutInMinutes = request.TimeoutInMinutes
            });
        }
    }
    public class TentativeMaxRequest
    {
        public int TentativeMax { get; set; }
    }
    public class SessionTimeoutRequest
    {
        public decimal TimeoutInMinutes { get; set; }
    }
}


