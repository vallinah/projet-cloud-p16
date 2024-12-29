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
        public DbSet<SessionConfig> SessionConfigs { get; set; }
        public DbSet<UserLogin> UserLogins { get; set; }
        public DbSet<SessionUser> SessionUsers { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Users>()
                .Property(u => u.UserId)
                .HasDefaultValueSql("generate_id('USER-', 'user_id_seq')");
            base.OnModelCreating(modelBuilder);
        }
    }
}
