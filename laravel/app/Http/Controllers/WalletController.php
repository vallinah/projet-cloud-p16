<?php

namespace App\Http\Controllers;

use App\Models\MouvementFond;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    protected $apiUrl = 'http://dotnet-api/api';

    public function index()
    {
        $user = Auth::user();
        $mouvements = MouvementFond::where('user_id', $user->user_id)
            ->orderBy('date_mouvement_fond', 'desc')
            ->get();

        $soldeDisponible = $this->getSoldeDisponible($user->user_id);

        return view('user.wallet.index', [
            'mouvements' => $mouvements,
            'solde' => $soldeDisponible,
            'title' => 'Gestion des fonds'
        ]);
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $validationCode = Str::random(32);

        try {
            // Créer le mouvement de fonds
            $mouvement = new MouvementFond();
            $mouvement->user_id = $user->user_id;
            $mouvement->montant_depot = $request->montant;
            $mouvement->date_mouvement_fond = now();
            $mouvement->code_validation = $validationCode;
            $mouvement->statut = 'en_attente';
            $mouvement->save();

            // Envoyer l'email via l'API
            $response = Http::post($this->apiUrl . '/Transaction/send-validation-email', [
                'userId' => $user->user_id,
                'email' => $user->email,
                'type' => 'depot',
                'montant' => $request->montant,
                'validationCode' => $validationCode
            ]);

            if ($response->failed()) {
                $mouvement->delete();
                return redirect()->back()->withErrors([
                    'email' => 'Erreur lors de l\'envoi de l\'email de confirmation.'
                ]);
            }

            return redirect()->route('wallet.index')
                ->with('success', 'Un email de confirmation a été envoyé. Veuillez valider votre dépôt.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement de votre demande.'
            ]);
        }
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $solde = $this->getSoldeDisponible($user->user_id);
        
        if ($solde < $request->montant) {
            return redirect()->back()
                ->with('error', 'Solde insuffisant pour effectuer ce retrait.');
        }

        $validationCode = Str::random(32);

        try {
            // Créer le mouvement de fonds
            $mouvement = new MouvementFond();
            $mouvement->user_id = $user->user_id;
            $mouvement->montant_retrait = $request->montant;
            $mouvement->date_mouvement_fond = now();
            $mouvement->code_validation = $validationCode;
            $mouvement->statut = 'en_attente';
            $mouvement->save();

            // Envoyer l'email via l'API
            $response = Http::post($this->apiUrl . '/Transaction/send-validation-email', [
                'userId' => $user->user_id,
                'email' => $user->email,
                'type' => 'retrait',
                'montant' => $request->montant,
                'validationCode' => $validationCode
            ]);

            if ($response->failed()) {
                $mouvement->delete();
                return redirect()->back()->withErrors([
                    'email' => 'Erreur lors de l\'envoi de l\'email de confirmation.'
                ]);
            }

            return redirect()->route('wallet.index')
                ->with('success', 'Un email de confirmation a été envoyé. Veuillez valider votre retrait.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement de votre demande.'
            ]);
        }
    }

    public function validateTransaction($code)
    {
        try {
            $mouvement = MouvementFond::where('code_validation', $code)
                ->where('statut', 'en_attente')
                ->firstOrFail();

            // Vérifier la validation avec l'API
            $response = Http::post($this->apiUrl . '/Transaction/validate', [
                'validationCode' => $code,
                'userId' => $mouvement->user_id
            ]);

            if ($response->successful() && $response->json()['status'] === 'success') {
                $mouvement->statut = 'validé';
                $mouvement->save();

                return redirect()->route('user.wallet.index')
                    ->with('success', 'Transaction validée avec succès !');
            }

            return redirect()->route('user.wallet.index')
                ->with('error', 'Code de validation invalide ou expiré.');

        } catch (\Exception $e) {
            return redirect()->route('user.wallet.index')
                ->with('error', 'Une erreur est survenue lors de la validation.');
        }
    }

    private function getSoldeDisponible($userId)
    {
        $depots = MouvementFond::where('user_id', $userId)
            ->where('statut', 'validé')
            ->sum('montant_depot');

        $retraits = MouvementFond::where('user_id', $userId)
            ->where('statut', 'validé')
            ->sum('montant_retrait');

        return $depots - $retraits;
    }
}