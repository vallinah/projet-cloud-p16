using AspNet.Models;
using Microsoft.EntityFrameworkCore;
namespace Aspnet.Models
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
            : base(options)
        {
        }

        public DbSet<Admin> Admins { get; set; }
        public DbSet<EmailPins> EmailPinss { get; set; }
        public DbSet<Token> Tokens { get; set; }
        public DbSet<Users> Userss { get; set; }
    }
}
