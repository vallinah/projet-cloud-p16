<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use App\Models\Cryptocurrency;
use App\Models\Commission;
use App\Models\MouvementCrypto;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{

    public function index()
    {
        $title = "Validation achat";
        $query = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->select('mouvement_crypto.*', 'cryptocurrencies.name as crypto_name')
            ->where('achat', 1)
            ->where('is_valid', false);
        $achats = $query->get();
        return view('admin.validation.achat', compact('title', 'achats'));
    }

    public function validerVente($id)
    {
        
        $vente = DB::table('mouvement_crypto')->where('id_mouvement_crypto', $id)->first();

        if (!$vente) {
            return redirect()->route('ventes')->with('error', 'Vente non trouvée.');
        }

        // Calcul du montant du retrait
        $montant_retrait = $vente->nombre * $vente->cours;

        // Marquer la vente comme validée
        DB::table('mouvement_crypto')
            ->where('id_mouvement_crypto', $id)
            ->update(['is_valid' => true]);

        // Insérer dans mouvement_fond
        DB::table('mouvement_fond')->insert([
            'montant_retrait' => null,
            'montant_depot' => $montant_retrait,
            'date_mouvement_fond' => $vente->date_mouvement,
            'user_id' => $vente->user_id,
            'is_valid' => true
        ]);

        $wallet = DB::table('crypto_wallets')
            ->where('user_id', $vente->user_id)
            ->where('crypto_id', $vente->crypto_id)
            ->first();


        // Mettre à jour le wallet existant (ajouter quantité + mettre à jour la date)
        DB::table('crypto_wallets')
            ->where('wallet_id', $wallet->wallet_id)
            ->update([
                'amount' => $wallet->amount - $vente->nombre,
                'updated_at' => $vente->date_mouvement
            ]);

        return redirect()->route('ventes')->with('success', 'Vente validée avec succès.');
    }
    public function validerAchat($id)
    {
            // Vérifiez si l'achat existe
            $achat = DB::table('mouvement_crypto')->where('id_mouvement_crypto', $id)->first();

            if (!$achat) {
                // Si l'achat n'existe pas, retournez un message d'erreur
                return redirect()->route('achats')->with('error', 'achat non trouvée.');
            }

            $montant_retrait = $achat->nombre * $achat->cours;
            DB::table('mouvement_crypto')
                ->where('id_mouvement_crypto', $id)
                ->update(['is_valid' => true]);

            DB::table('mouvement_fond')->insert([
                'montant_retrait' => $montant_retrait,
                'montant_depot' => null,
                'date_mouvement_fond' => $achat->date_mouvement,
                'user_id' => $achat->user_id,
                'is_valid' => true
            ]);

            DB::table('mouvement_crypto')
                ->where('id_mouvement_crypto', $id)
                ->update(['is_valid' => true]);

            $wallet = DB::table('crypto_wallets')
                ->where('user_id', $achat->user_id)
                ->where('crypto_id', $achat->crypto_id)
                ->first();

            if ($wallet) {
                // Mettre à jour le wallet existant (ajouter quantité + mettre à jour la date)
                DB::table('crypto_wallets')
                    ->where('wallet_id', $wallet->wallet_id)
                    ->update([
                        'amount' => $wallet->amount + $achat->nombre,
                        'updated_at' => $achat->date_mouvement
                    ]);
            } else {
                DB::table('crypto_wallets')->insert([
                    'wallet_id' => DB::raw("generate_id('CRY-', 'wallet_id_seq')"), // Générer un ID
                    'user_id' => $achat->user_id,
                    'crypto_id' => $achat->crypto_id,
                    'amount' => $achat->nombre,
                    'created_at' => $achat->date_mouvement,
                    'updated_at' => $achat->date_mouvement
                ]);
            }
            return redirect()->route('achats')->with('success', 'achat validée avec succès.');
        }
        

}