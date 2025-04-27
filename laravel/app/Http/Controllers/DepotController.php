<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use App\Models\Cryptocurrency;
use App\Models\Commission;
use App\Models\MouvementCrypto;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepotController extends Controller
{

    public function index()
    {
        $title = "Validation depot";
        $query = DB::table('mouvement_fond')
            ->whereNotNull('montant_depot')
            ->where('is_valid', false);
        $depots = $query->get();
        return view('admin.validation.depot', compact('title', 'depots'));
    }

    public function validerDepot($id)
    {
        // Vérifiez si l'depot existe
        $depot = DB::table('mouvement_fond')->where('id_mouvement_fond', $id)->first();

        if (!$depot) {
            // Si l'depot n'existe pas, retournez un message d'erreur
            return redirect()->route('depots')->with('error', 'depot non trouvée.');
        }

        // Mettez à jour la depot pour la marquer comme validée
        DB::table('mouvement_fond')
            ->where('id_mouvement_fond', $id)
            ->update(['is_valid' => true]);

        // Optionnel : Vous pouvez aussi mettre à jour d'autres informations comme la date de validation, etc.
        // Exemple : DB::table('mouvement_fond')->where('id', $id)->update(['validation_date' => now()]);

        // Redirigez avec un message de succès
        return redirect()->route('depots')->with('success', 'depot validée avec succès.');
    }


}