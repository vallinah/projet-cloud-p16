<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use App\Models\Cryptocurrency;
use App\Models\Commission;
use App\Models\MouvementCrypto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    public function index(Request $request)
    {
        $title = "Listes des Opérations";
        
        // Récupérer tous les utilisateurs et cryptomonnaies pour le filtre
        $users = DB::table('users')->select('user_id', 'first_name')->get();
        $cryptocurrencies = DB::table('cryptocurrencies')->select('crypto_id', 'name')->get();
        
        // Récupération des paramètres du filtre
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $userId = $request->input('user_id', 'all');
        $cryptoId = $request->input('crypto_id', 'all');
        
        // Requête de base pour récupérer les opérations validées
        $query = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->join('users', 'mouvement_crypto.user_id', '=', 'users.user_id')
            ->select(
                'mouvement_crypto.*',
                'cryptocurrencies.name as crypto_name',
                'users.*'
            )
            ->where('mouvement_crypto.is_valid', true);
        
        // Filtrage par date
        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
        } elseif ($dateDebut) {
            $query->where('date_mouvement', '>=', $dateDebut);
        } elseif ($dateFin) {
            $query->where('date_mouvement', '<=', $dateFin);
        }
        
        // Filtrage par utilisateur
        if ($userId !== 'all') {
            $query->where('mouvement_crypto.user_id', $userId);
        }
        
        // Filtrage par cryptomonnaie
        if ($cryptoId !== 'all') {
            $query->where('mouvement_crypto.crypto_id', $cryptoId);
        }
        
        // Exécuter la requête
        $operations = $query->get();
        
        return view('admin.historique.operation', compact(
            'title', 
            'operations', 
            'users', 
            'cryptocurrencies', 
            'dateDebut', 
            'dateFin', 
            'userId', 
            'cryptoId'
        ));
    }

    public function get_by_id($id, Request $request)
    {
        $title = "Listes des Operations";

        // Récupérer tous les utilisateurs et cryptomonnaies pour le filtre
        $users = DB::table('users')->select('user_id', 'first_name')->get();
        $cryptocurrencies = DB::table('cryptocurrencies')->select('crypto_id', 'name')->get();

        // Récupérer les paramètres de filtrage
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $cryptoId = $request->input('crypto_id');

        // Requête de base pour récupérer les opérations validées
        $query = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->join('users', 'mouvement_crypto.user_id', '=', 'users.user_id')
            ->select(
                'mouvement_crypto.*',
                'cryptocurrencies.name as crypto_name',
                'users.*'
            )
            ->where('mouvement_crypto.is_valid', true)
            ->where('mouvement_crypto.user_id', $id);

        // Filtrage par date
        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
        } elseif ($dateDebut) {
            $query->where('date_mouvement', '>=', $dateDebut);
        } elseif ($dateFin) {
            $query->where('date_mouvement', '<=', $dateFin);
        }

        // Filtrage par cryptomonnaie
        if ($cryptoId) {
            $query->where('mouvement_crypto.crypto_id', $cryptoId);
        }

        // Exécuter la requête
        $operations = $query->get();

        return view('admin.historique.operation', compact(
            'title', 
            'operations', 
            'users', 
            'cryptocurrencies', 
            'dateDebut', 
            'dateFin', 
            'cryptoId'
        ));
    }
}