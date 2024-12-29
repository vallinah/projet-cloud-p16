using Aspnet.Models;
using AspNet.Models;
using MailKit.Net.Smtp;
using MailKit.Security;
using Microsoft.EntityFrameworkCore;
using MimeKit;

namespace Aspnet.Services
{
    public class UsersService
    {
        private readonly ApplicationDbContext _context;
        private readonly TokenService _tokenService;
        private readonly PinCodeService _pinCodeService;
        private readonly UtilService _utilService;
        // private readonly IUsersRepository _usersRepository;

        public UsersService(ApplicationDbContext context, TokenService tokenService, PinCodeService pinCodeService, UtilService utilService)
        {
            _context = context;
            _tokenService = tokenService;
            _pinCodeService = pinCodeService;
            _utilService = utilService;
        }

        public async Task<Users?> AuthenticateUsersAsync(string email, string password)
        {
            var users = await _context.Userss
                .Where(a => a.Email == email)
                .FirstOrDefaultAsync();

            if (users == null || users.Password != _utilService.HashPassword(password))
            {
                return null;
            }

            return users;
        }

        public async Task<Users?> GetUsersByEmailAsync(string email)
        {
            var users = await _context.Userss
                .Where(a => a.Email == email)
                .FirstOrDefaultAsync();

            if (users == null)
            {
                return null;
            }

            return users;
        }

        public async Task AddUsersAsync(Users users)
        {
            try
            {
                await _context.Userss.AddAsync(users);
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

        public async Task<Users?> GetUserByIdAsync(string userId)
        {
            return await _context.Userss
                .FirstOrDefaultAsync(u => u.UserId == userId);
        }

        public async Task<bool> DeactivateAccount(string userId)
        {
            try
            {
                Users? user = await GetUserByIdAsync(userId);
                if (user != null)
                {
                    string pinCode = _pinCodeService.GeneratePinCode();
                    Token token = new Token();
                    token.TokenCode = pinCode;
                    token.CreatedDate = DateTime.UtcNow;
                    token.UserId = userId;
                    await _tokenService.AddTokenAsync(token);
                    user.IsValid = false;
                    await _context.SaveChangesAsync();
                    SendActivationEmail(user.Email, pinCode);
                    return true;
                }
                return false;
            }
            catch (System.Exception ex)
            {
                throw new Exception($"Exception lors de la desactivation du compte de l'user:{userId} ", ex);
            }
        }

        public async Task<bool> ActivateAccount(string userId, string token)
        {
            try
            {
                Console.WriteLine($"recu:{userId}/{token}");
                Users? user = await GetUserByIdAsync(userId);
                Console.WriteLine($" {user?.LastName}");

                if (user == null)
                {
                    Console.WriteLine("Utilisateur introuvable");
                    return false;
                }

                Token? tokenCode = await _tokenService.GetTokenByIdUserAsync(userId);
                Console.WriteLine($" {tokenCode?.TokenCode}");

                if (tokenCode == null)
                {
                    Console.WriteLine("Token introuvable");
                    return false;
                }

                Console.WriteLine($"base:{user.UserId}/{tokenCode.TokenCode}");
                if (tokenCode.TokenCode.Equals(token))
                {
                    user.IsValid = true;
                    await _context.SaveChangesAsync();
                    await _tokenService.DeleteTokenByIdUserAsync(userId);
                    return true;
                }

                Console.WriteLine("Le token ne correspond pas");
                return false;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Erreur lors de l'activation du compte: {ex.Message}");
                return false;
            }
        }


        public void SendActivationEmail(string toEmail, string activationToken)
        {
            // Création du message
            var emailMessage = new MimeMessage();
            emailMessage.From.Add(new MailboxAddress("Admin", "otisoavallinah@gmail.com"));
            emailMessage.To.Add(MailboxAddress.Parse(toEmail)); // Simplification
            emailMessage.Subject = "Activation de votre compte";
            emailMessage.Body = new TextPart("plain")
            {
                Text = $"Bonjour,\n\nCliquez sur le lien ci-dessous pour activer votre compte :\n" +
                          $"http://localhost:5000/api/users/activate?token={activationToken}\n\nCordialement."
            };

            try
            {
                using (var client = new SmtpClient())
                {
                    // Connexion à Gmail en utilisant STARTTLS (port 587)
                    client.Connect("smtp.gmail.com", 587, SecureSocketOptions.StartTls);

                    // Authentification avec vos identifiants Gmail
                    client.Authenticate("otisoavallinah@gmail.com", "ampq arsb rjbc zzrx");

                    // Envoi de l'email
                    client.Send(emailMessage);

                    // Déconnexion après envoi
                    client.Disconnect(true);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error sending email: {ex.Message}");
                Console.WriteLine(ex.StackTrace); // Pour obtenir plus de détails sur l'erreur
            }
        }
        public async Task<UserLogin?> GetUserLoginAsync(string userId)
        {
            return await _context.UserLogins
                .Where(ul => ul.UserId == userId)
                .FirstOrDefaultAsync();
        }

        public async Task<bool> UpdateUser(Users newUsers)
        {
            Users? user_existant = await GetUserByIdAsync(newUsers.UserId);
            // raha tsy misy ilay User de miretourne false
            if (user_existant == null)
            {
                return false;
            }
            else
            {
                // donner l eMAIL DE l ancien user au nouveau
                newUsers.Email = user_existant.Email;
                user_existant.FirstName = newUsers.FirstName;
                user_existant.LastName = newUsers.LastName;
                user_existant.Password = newUsers.Password;
                user_existant.DateOfBirth = newUsers.DateOfBirth.ToUniversalTime();
                user_existant.IsValid = newUsers.IsValid;

                _context.Userss.Update(user_existant);
                await _context.SaveChangesAsync();
                return true;
            }
        }
    }
}
