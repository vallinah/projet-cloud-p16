<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Cryptocurrency;
use Kreait\Firebase\Factory;

class UpdateCryptoPricesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Log::info('Début du job UpdateCryptoPricesJob');
        
        $cryptocurrencies = Cryptocurrency::all();
        \Log::info('Nombre de cryptomonnaies trouvées : ' . $cryptocurrencies->count());
    
        $firebase = (new Factory)
            ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
            ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
            ->createDatabase();
        
        try {
            foreach ($cryptocurrencies as $crypto) {
                $currentPrice = $crypto->current_price;
                $variation = rand(-500, 500) / 100;
                $newPrice = max(0, $currentPrice + ($currentPrice * ($variation / 100)));
        
                $reference = $firebase->getReference('cryptocurrencies/' . $crypto->crypto_id);
                $data = [
                    'name' => $crypto->name,
                    'symbol' => $crypto->symbol,
                    'current_price' => $newPrice,
                    'updated_date' => now()->toDateTimeString(),
                ];
                
                \Log::info('Tentative d\'écriture pour ' . $crypto->name, $data);
                
                $reference->set($data);
                
                \Log::info('Écriture réussie pour ' . $crypto->name);
                
                $crypto->update([
                    'current_price' => $newPrice,
                    'updated_date' => now(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour Firebase : ' . $e->getMessage());
            throw $e;
        }
    }
}
