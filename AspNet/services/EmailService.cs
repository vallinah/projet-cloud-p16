using MailKit.Net.Smtp;
using MailKit.Security;
using MimeKit;
using System;

public class EmailService
{
    public void SendPinCodeEmail(string toEmail, string pinCode)
    {
        // Création du message
        var emailMessage = new MimeMessage();
        emailMessage.From.Add(new MailboxAddress("Admin", "otisoavallinah@gmail.com"));
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
}
