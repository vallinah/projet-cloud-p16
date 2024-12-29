using System;

namespace Aspnet.Services
{
    public class PinCodeService
    {
        private readonly Random _random = new Random();

        public string GeneratePinCode()
        {
            // Générer un code PIN aléatoire de 6 chiffres
            return _random.Next(100000, 999999).ToString();
        }
    }
}
