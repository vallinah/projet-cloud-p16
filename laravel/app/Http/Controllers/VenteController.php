<?php

namespace App\Http\Controllers;

use App\Models\Cryptocurrency;
use App\Models\Commission;
use App\Models\MouvementCrypto;
use App\Http\Controllers\MouvementFondController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;

class VenteController extends Controller
{
    private $database;

    public function __construct()
    {
        // Initialisation de Firebase
        $this->database = (new Factory)
            ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
            ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
            ->createDatabase();
    }

    public function index()
    {
        $title = "Validation vente";
        $query = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->select('mouvement_crypto.*', 'cryptocurrencies.name as crypto_name')
            ->where('vente', 1)
            ->where('is_valid', false);
        $ventes = $query->get();
        return view('admin.validation.vente', compact('title', 'ventes'));
    }

    public function validerVente($id)
    {
        // Vérifiez si l'achat existe
        $vente = DB::table('mouvement_crypto')->where('id_mouvement_crypto', $id)->first();

        if (!$vente) {
            // Si l'achat n'existe pas, retournez un message d'erreur
            return redirect()->route('ventes')->with('error', 'Vente non trouvée.');
        }

        // Mettez à jour la vente pour la marquer comme validée
        DB::table('mouvement_crypto')
            ->where('id_mouvement_crypto', $id)
            ->update(['is_valid' => true]);

        // Optionnel : Vous pouvez aussi mettre à jour d'autres informations comme la date de validation, etc.
        // Exemple : DB::table('mouvement_crypto')->where('id', $id)->update(['validation_date' => now()]);

        // Redirigez avec un message de succès
        return redirect()->route('ventes')->with('success', 'Vente validée avec succès.');
    }

    public function evolution()
    {
        $title = "evolution";
        $cryptocurrencies = Cryptocurrency::all();
        return view('user.vente.index', compact('title', 'cryptocurrencies'));
    }
    public function historiquevente()
    {
        $title = "Historique operations";
        $history = MouvementCrypto::all();
        return view('user.vente.vente', compact('title', 'history'));
    }
    public function evolutionCrypto(Request $request)
    {
        $title = "Evolution crypto";
        $request->validate([
            'crypto_id' => 'required|string|exists:cryptocurrencies,crypto_id',
        ]);

        $user_id = Auth::id();
        $crypto_id = $request->input('crypto_id');
        $userHasCrypto = DB::table('crypto_wallets')
            ->where('user_id', $user_id)
            ->where('crypto_id', $crypto_id)
            ->get();

        $priceHistory = DB::table('crypto_price_history')
            ->where('crypto_id', $crypto_id)
            ->orderBy('change_date', 'DESC')
            ->limit(10)
            ->get();

        $cryptoname = DB::table('cryptocurrencies')
            ->where('crypto_id', $crypto_id)
            ->first();    

        return view('user.vente.resultevolution', compact('title', 'priceHistory', 'userHasCrypto', 'crypto_id','cryptoname'));
    }

    public function getHistory($crypto_id)
    {
        $user_id = Auth::id();
        $userHasCrypto = DB::table('crypto_wallets')
            ->where('user_id', $user_id)
            ->where('crypto_id', $crypto_id)
            ->exists();
        $priceHistory = DB::table('crypto_price_history')
            ->where('crypto_id', $crypto_id)
            ->orderBy('change_date', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'priceHistory' => $priceHistory,
            'crypto_id' => $crypto_id,
            'userHasCrypto' => $userHasCrypto
        ]);

    }

    public function sellCrypto(Request $request)
    {
        $request->validate([
            'crypto_id' => 'required|string|exists:cryptocurrencies,crypto_id',
            'quantity' => 'required|numeric|min:0.00000001',
            'action' => 'required|string|in:buy,sell',
        ]);
    
        $crypto = DB::table('cryptocurrencies')
            ->where('crypto_id', $request->input('crypto_id'))
            ->first();
    
        if (!$crypto) {
            return back()->with('error', 'Cryptomonnaie introuvable.');
        }
    
        $userId = Auth::id();
        $quantity = $request->input('quantity');
        $currentPrice = DB::table('cryptocurrencies')
            ->where('crypto_id', $crypto->crypto_id)
            ->value('current_price');
    
        if (!$currentPrice) {
            return back()->with('error', 'Prix de la cryptomonnaie introuvable.');
        }
        $mouvementFondController = new MouvementFondController();
        $solde = $mouvementFondController->getSoldeDisponible($userId);
        

        if ($request->input('action') === 'buy') {
            if($solde < ($quantity* $currentPrice)) {
                return $this->getHistory($crypto->crypto_id);
                //return redirect()->back()->with('erreur', 'Solde insuffisant pour effectuer cet achat.');
            }
        }

        $commission = Commission::first();
    
        // Données à insérer
        $mouvementData = [
            'nombre' => $quantity,
            'cours' => $currentPrice,
            'vente' => $request->input('action') === 'sell' ? 1 : null,
            'achat' => $request->input('action') === 'buy' ? 1 : null,
            'crypto_id' => $crypto->crypto_id,
            'user_id' => $userId,
            'date_mouvement' => now()->toDateTimeString(),
            'is_valid' => false,
            'id_commission' => $commission->id_commission,
        ];
    
        DB::beginTransaction();
    
        try {
            // Insertion dans la base de données locale
            DB::table('mouvement_crypto')->insert($mouvementData);
    
            // Insertion dans Firebase
            $this->database
                ->getReference('mouvement_crypto/' . $userId)
                ->push($mouvementData);
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Vente enregistrée avec succès.',
                'data' => $mouvementData,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de l\'enregistrement de la vente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

}