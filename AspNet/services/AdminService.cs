using Aspnet.Models;
using Microsoft.EntityFrameworkCore;
using System.Linq;
using System.Threading.Tasks;

namespace Aspnet.Services
{
    public class AdminService
    {
        private readonly ApplicationDbContext _context;

        public AdminService(ApplicationDbContext context)
        {
            _context = context;
        }

        public async Task<Admin?> AuthenticateAdminAsync(string login, string password)
        {
            var admin = await _context.Admins
                .Where(a => a.Login == login)
                .FirstOrDefaultAsync();

            if (admin == null || admin.Password != password)
            {
                return null;
            }

            return admin;
        }

    }
}
