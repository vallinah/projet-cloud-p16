using Aspnet.Models;
using Microsoft.EntityFrameworkCore;
using System;
using System.Threading.Tasks;

namespace Aspnet.Services
{
    public class SessionUserService
    {
        private readonly ApplicationDbContext _context;

        public SessionUserService(ApplicationDbContext context)
        {
            _context = context;
        }

        public async Task InsertSessionAsync(string userId, string name, string value)
        {
            var sessionUser = new SessionUser
            {
                UserId = userId,
                Name = name,
                Value = value,
                CreatedDate = DateTime.UtcNow
            };

            _context.SessionUsers.Add(sessionUser);
            await _context.SaveChangesAsync();
        }

        public async Task<SessionUser> GetSessionByUserIdAndNameAsync(string userId, string name)
        {
            var sessionUser = await _context.SessionUsers
                                            .FirstOrDefaultAsync(s => s.UserId == userId && s.Name == name);

            if (sessionUser == null)
            {
                return null;
            }
            var sessionConfig = await _context.SessionConfigs.FirstOrDefaultAsync();
            if (sessionConfig == null)
            {
                throw new InvalidOperationException("La configuration de la session est manquante.");
            }
            DateTime expirationDate = sessionUser.CreatedDate.AddMinutes((double)sessionConfig.MinuteTimeout);
            if (expirationDate < DateTime.UtcNow)
            {
                await DeleteSessionAsync(sessionUser.UserId, sessionUser.Name);
                return null;
            }

            return sessionUser;
        }

        // Méthode pour supprimer une session
        public async Task DeleteSessionAsync(string userId, string name)
        {
            var sessionUser = await _context.SessionUsers
                                             .FirstOrDefaultAsync(s => s.UserId == userId && s.Name == name);

            if (sessionUser != null)
            {
                _context.SessionUsers.Remove(sessionUser);
                await _context.SaveChangesAsync();
            }
        }
    }
}