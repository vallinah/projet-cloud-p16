using Aspnet.Models;
using AspNet.Models;
using Microsoft.EntityFrameworkCore;
using System.Linq;
using System.Threading.Tasks;

namespace Aspnet.Services
{
    public class UtilService
    {
        private readonly ApplicationDbContext _context;

        public UtilService(ApplicationDbContext context)
        {
            _context = context;
        }

        private string HashPassword(string password)
        {
            using (var sha256 = System.Security.Cryptography.SHA256.Create())
            {
                byte[] bytes = sha256.ComputeHash(System.Text.Encoding.UTF8.GetBytes(password));
                return Convert.ToBase64String(bytes);
            }
        }
    }
}