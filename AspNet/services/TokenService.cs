using Aspnet.Models;
using AspNet.Models;
using MailKit.Net.Smtp;
using MailKit.Security;
using Microsoft.EntityFrameworkCore;
using MimeKit;

namespace Aspnet.Services
{
    public class TokenService
    {
        private readonly ApplicationDbContext _context;

        public TokenService(ApplicationDbContext context)
        {
            _context = context;
        }

        public async Task AddTokenAsync(Token token)
        {
            try
            {
                await _context.Tokens.AddAsync(token);
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateException dbEx)
            {
                Console.WriteLine($"Database update error: {dbEx.InnerException?.Message ?? dbEx.Message}");
                throw;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Unexpected error: {ex.Message}");
                throw;
            }
        }

        public async Task<Token?> GetTokenByIdUserAsync(string userId)
        {
            return await _context.Tokens
                .FirstOrDefaultAsync(t => t.UserId == userId);
        }

        public async Task DeleteTokenByIdUserAsync(string userId)
        {
            Token? token = await GetTokenByIdUserAsync(userId);
            if (token != null)
            {
                _context.Tokens.Remove(token);
                await _context.SaveChangesAsync();
                Console.WriteLine("voafafa o");
            }
        }
    }
}
