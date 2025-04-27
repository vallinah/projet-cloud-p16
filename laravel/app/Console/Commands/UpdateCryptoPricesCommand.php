<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cryptocurrency;

class UpdateCryptoPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mise à jour des prix des cryptomonnaies toutes les 10 secondes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            // Mettre à jour les prix des cryptomonnaies directement
            $cryptocurrencies = Cryptocurrency::all();

            foreach ($cryptocurrencies as $crypto) {
                // Générer un prix aléatoire basé sur le prix actuel
                $currentPrice = $crypto->current_price;
                $variation = rand(-500, 500) / 100; // Variation de -5% à +5%
                $newPrice = max(0, $currentPrice + ($currentPrice * ($variation / 100)));

                // Mettre à jour le prix
                $crypto->update([
                    'current_price' => $newPrice,
                    'updated_date' => now(),
                ]);
            }

            // Attendre 10 secondes
            sleep(10);
        }
    }
}
