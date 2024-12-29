using Aspnet.Models;
using Microsoft.EntityFrameworkCore;
using System.Threading.Tasks;

namespace Aspnet.Services
{
    public class SessionConfigService
    {
        private readonly ApplicationDbContext _context;

        public SessionConfigService(ApplicationDbContext context)
        {
            _context = context;
        }

        public async Task<int> GetTentativeMaxAsync()
        {
            var sessionConfig = await _context.SessionConfigs.FirstOrDefaultAsync();
            return sessionConfig?.TentativeMax ?? 3;
        }
        public async Task<bool> SetTentativeMaxAsync(int tentativeMax)
        {
            var sessionConfig = await _context.SessionConfigs.FirstOrDefaultAsync();

            if (sessionConfig == null)
            {
                _context.SessionConfigs.Add(new SessionConfig
                {
                    TentativeMax = tentativeMax
                });
            }
            else
            {
                sessionConfig.TentativeMax = tentativeMax;
                _context.SessionConfigs.Update(sessionConfig);
            }

            await _context.SaveChangesAsync();
            return true;
        }

        // Vérifie si l'utilisateur a dépassé les tentatives maximales
        public async Task<bool> MaxTentativesAsync(string userId)
        {
            var maxAttempts = await GetTentativeMaxAsync();

            var userLogin = await _context.UserLogins
                .FirstOrDefaultAsync(u => u.UserId == userId);

            return userLogin?.Tentative >= maxAttempts;
        }

        // Incrémente les tentatives de connexion pour un utilisateur
        public async Task IncrementTentativeAsync(string userId, string email)
        {
            var userLogin = await _context.UserLogins
                .FirstOrDefaultAsync(u => u.UserId == userId);

            if (userLogin == null)
            {
                _context.UserLogins.Add(new UserLogin
                {
                    UserId = userId,
                    Tentative = 1,
                    Email = email
                });
            }
            else
            {
                userLogin.Tentative++;
                _context.UserLogins.Update(userLogin);
            }

            await _context.SaveChangesAsync();
        }

        // Réinitialise les tentatives après une connexion réussie
        public async Task ResetTentativeAsync(string userId)
        {
            var userLogin = await _context.UserLogins
                .FirstOrDefaultAsync(u => u.UserId == userId);

            if (userLogin != null)
            {
                userLogin.Tentative = 0;
                _context.UserLogins.Update(userLogin);
                await _context.SaveChangesAsync();
            }
        }

        public async Task<decimal> GetMinuteTimeoutAsync()
        {
            var sessionConfig = await _context.SessionConfigs.FirstOrDefaultAsync();
            if (sessionConfig == null)
            {
                return 30;
            }

            return sessionConfig.MinuteTimeout;
        }

        public async Task<bool> SetMinuteTimeoutAsync(decimal timeoutInMinutes)
        {
            if (timeoutInMinutes <= 0)
            {
                return false;
            }
            var sessionConfig = await _context.SessionConfigs.FirstOrDefaultAsync();
            if (sessionConfig == null)
            {
                return false;
            }
            sessionConfig.MinuteTimeout = timeoutInMinutes;

            await _context.SaveChangesAsync();
            return true;
        }
    }
}


