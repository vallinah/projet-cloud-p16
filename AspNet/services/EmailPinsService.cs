using Aspnet.Models;
using AspNet.Models;
using MailKit.Net.Smtp;
using MailKit.Security;
using Microsoft.EntityFrameworkCore;
using MimeKit;

namespace Aspnet.Services
{
    public class EmailPinsService
    {
        private readonly ApplicationDbContext _context;

        public EmailPinsService(ApplicationDbContext context)
        {
            _context = context;
        }

        public async Task AddEmailPinsAsync(EmailPins emailPins)
        {
            try
            {
                await _context.EmailPinss.AddAsync(emailPins);
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


        public void SendPinCodeEmail(string toEmail, string pinCode)
        {
            // Création du message
            var emailMessage = new MimeMessage();
            emailMessage.From.Add(new MailboxAddress("Admin", "antsamadagascar@gmail.com"));
            emailMessage.To.Add(MailboxAddress.Parse(toEmail)); // Simplification
            emailMessage.Subject = "Your PIN Code";
            emailMessage.Body = new TextPart("plain")
            {
                Text = $"Your PIN code is: {pinCode}. It is valid for 90 seconds."
            };

            try
            {
                using (var client = new SmtpClient())
                {
                    // Connexion à Gmail en utilisant STARTTLS (port 587)
                    client.Connect("smtp.gmail.com", 587, SecureSocketOptions.StartTls);

                    // Authentification avec vos identifiants Gmail
                    client.Authenticate("antsamadagascar@gmail.com", "eong yuko uxmz yakl");

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


        public async Task<EmailPins?> GetEmailPinsByUserIdAsync(string userId)
        {
            return await _context.EmailPinss
                .Where(ep => ep.UserId == userId) // Filtre par utilisateur
                .OrderByDescending(ep => ep.CreatedDate) // Trie pour obtenir le plus récent
                .Take(1) // Limite à un seul enregistrement
                .FirstOrDefaultAsync(); // Retourne le premier ou null
        }

        public async Task<bool> CheckPinCode(string UserId, string pinCode, DateTime receivedDate)
        {
            EmailPins? emailPins = await GetEmailPinsByUserIdAsync(UserId);
            if (emailPins != null)
            {
                if (emailPins.CodePin == pinCode && receivedDate <= emailPins.ExpirationDate)
                {
                    return true;
                }
                else
                {
                    throw new InvalidOperationException($"Verify the pin code you enter");
                }
            }
            else
            {
                throw new InvalidOperationException($"No EmailPins found for UserId: {UserId}");
            }
        }
    }
}
