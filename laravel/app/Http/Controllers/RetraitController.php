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

class RetraitController extends Controller
{

    public function index()
    {
        $title = "Validation retrait";
        $query = DB::table('mouvement_fond')
            ->whereNotNull('montant_retrait')
            ->where('is_valid', false);
        $retraits = $query->get();
        return view('admin.validation.retrait', compact('title', 'retraits'));
    }

    public function validerRetrait($id)
    {
        // Vérifiez si l'retrait existe
        $retrait = DB::table('mouvement_fond')->where('id_mouvement_fond', $id)->first();

        if (!$retrait) {
            // Si l'retrait n'existe pas, retournez un message d'erreur
            return redirect()->route('retraits')->with('error', 'retrait non trouvée.');
        }

        // Mettez à jour la retrait pour la marquer comme validée
        DB::table('mouvement_fond')
            ->where('id_mouvement_fond', $id)
            ->update(['is_valid' => true]);

        // Optionnel : Vous pouvez aussi mettre à jour d'autres informations comme la date de validation, etc.
        // Exemple : DB::table('mouvement_fond')->where('id', $id)->update(['validation_date' => now()]);

        // Redirigez avec un message de succès
        return redirect()->route('retraits')->with('success', 'retrait validée avec succès.');
    }


}