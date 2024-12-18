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

        // Méthode pour vérifier le login et le mot de passe de l'administrateur
        public async Task<Admin?> AuthenticateAdminAsync(string login, string password)
        {
            var admin = await _context.Admins
                .Where(a => a.Login == login)
                .FirstOrDefaultAsync();

            if (admin == null || admin.Password != password)
            {
                return null; // Authentification échouée
            }

            return admin; // Authentification réussie
        }

        // Exemple de fonction de hachage de mot de passe
        private string HashPassword(string password)
        {
            // Utilisez une méthode sécurisée pour le hachage des mots de passe (ex. PBKDF2, bcrypt, etc.)
            using (var sha256 = System.Security.Cryptography.SHA256.Create())
            {
                byte[] bytes = sha256.ComputeHash(System.Text.Encoding.UTF8.GetBytes(password));
                return Convert.ToBase64String(bytes);
            }
        }
    }
}
