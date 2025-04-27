<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\Typeanalyse;
use App\Models\Cryptocurrency;
use App\Models\MouvementCrypto;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class CommissionController extends Controller
{
    // Affiche la commission actuelle
    public function index()
    {
        $title = "Commission";
        $commission = Commission::latest('date_commission')->first();

        if (!$commission) {
            return response()->json(['message' => 'Aucune commission trouvée.'], 404);
        }

        return view('user.commission.index', compact('title', 'commission'));
    }

    public function showAnalysCommission()
    {
        $cryptocurrencies = Cryptocurrency::all();
        $types= Typeanalyse::all();
        return view('user.commission.analyse', [
            'cryptocurrencies' => $cryptocurrencies,
            'title' => 'Analyse des commissions',
            'types'=> $types
        ]);
    }

    public function update(Request $request, $id)  // On récupère l'ID de la commission à mettre à jour
    {
        $validated = $request->validate([
            'valeur' => 'required|numeric|min:0',
            'date_commission' => 'required|date',
        ]);
    
        try {
            $commission = Commission::findOrFail($id);
            $commission->valeur = $validated['valeur'];
            $commission->date_commission = Carbon::parse($validated['date_commission']);  // Conversion de la date en objet Carbon
            $commission->save();
            return redirect()->route('commission')->with('success', 'Commission mise à jour avec succès.');
    
        } catch (\Exception $e) {
            // En cas d'erreur, retourne avec message d'erreur
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function analyseCommission(Request $request)
    {
        $request->validate([
            'analysis_type' => 'required|in:somme,moyenne',
            'crypto' => 'nullable|exists:cryptocurrencies,crypto_id',
            'start_datetime' => 'nullable|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
        ]);

        $analysisType = $request->input('analysis_type'); 
        $cryptoId = $request->input('crypto');
        $startDatetime = $request->input('start_datetime');
        $endDatetime = $request->input('end_datetime');

        $query = DB::table('v_mouvement_commission');


        if ($cryptoId && $cryptoId !== 'all') {
            $query->where('crypto_id', $cryptoId);
        }

        if ($startDatetime) {
            $query->where('date_mouvement', '>=', $startDatetime);
        }
        if ($endDatetime) {
            $query->where('date_mouvement', '<=', $endDatetime);
        }

        $results = $query->get();

        $totalCommission = $results->sum(function($row) {
            return round($row->total_commission, 10); // Assurez-vous d'utiliser une précision élevée
        });

        $totalCount = $results->count();
        $crypto_name = $results->isNotEmpty() ? $results->first()->name : null;

        if ($analysisType === 'somme') {
            $result = $totalCommission; 
        } elseif ($analysisType === 'moyenne') {
            $result = $totalCount > 0 ? $totalCommission / $totalCount : 0;
        }

        return view('user.commission.result', [
            'title' => 'Resultat analyse',
            'result' => $result,
            'analysis_type' => $analysisType,
            'crypto_id' => $cryptoId,
            'name'=> $crypto_name,
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
        ]);
    }
}

