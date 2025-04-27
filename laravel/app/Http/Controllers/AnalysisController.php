<?php

// app/Http/Controllers/AnalysisController.php

namespace App\Http\Controllers;

use App\Models\Cryptocurrency;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function showAnalysisForm()
    {
        $cryptocurrencies = Cryptocurrency::all();
        return view('analysis.form', [
            'cryptocurrencies' => $cryptocurrencies,
            'title' => 'Analyse des Cryptomonnaies'
        ]);
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'analysis_type' => 'required|in:quartile,max,min,average,standard_deviation',
            'cryptocurrencies' => 'required|array|min:1',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime'
        ], [
            'cryptocurrencies.required' => 'Veuillez sélectionner au moins une cryptomonnaie.',
            'cryptocurrencies.min' => 'Veuillez sélectionner au moins une cryptomonnaie.',
            'start_datetime.required' => 'La date de début est requise.',
            'end_datetime.required' => 'La date de fin est requise.',
            'end_datetime.after' => 'La date de fin doit être postérieure à la date de début.'
        ]);

            $query = Cryptocurrency::query();

            $query->whereBetween('created_date', [
                Carbon::parse($request->start_datetime),
                Carbon::parse($request->end_datetime)
            ]);

            if (!in_array('all', $request->cryptocurrencies)) {
                $query->whereIn('crypto_id', $request->cryptocurrencies);
            }

            $selectedCryptos = $query->get();
            
            if ($selectedCryptos->isEmpty()) {
                return view('analysis.form', [
                    'cryptocurrencies' => Cryptocurrency::all(),
                    'error' => 'Aucune donnée trouvée pour la période sélectionnée.',
                    'startDate' => $request->start_datetime,
                    'endDate' => $request->end_datetime,
                    'title' => 'Analyse des Cryptomonnaies'
                ]);
            }

            $prices = $selectedCryptos->pluck('current_price')->toArray();

            $analysisResult = match($request->analysis_type) {
                'quartile' => $this->calculateQuartile($prices),
                'max' => max($prices),
                'min' => min($prices),
                'average' => round(array_sum($prices) / count($prices), 8),
                'standard_deviation' => round($this->calculateStandardDeviation($prices), 8)
            };


            $analysisTypeDisplay = match($request->analysis_type) {
                'quartile' => '1er Quartile',
                'max' => 'Maximum',
                'min' => 'Minimum',
                'average' => 'Moyenne',
                'standard_deviation' => 'Écart-type'
            };
            try {
                $selectedCryptoIds = !in_array('all', $request->cryptocurrencies) 
                    ? $request->cryptocurrencies 
                    : Cryptocurrency::pluck('crypto_id')->toArray();
        
                $availableCryptos = Cryptocurrency::whereIn('crypto_id', $selectedCryptoIds)
                    ->whereBetween('created_date', [
                        Carbon::parse($request->start_datetime),
                        Carbon::parse($request->end_datetime)
                    ])
                    ->get();
        
                $selectedCryptoNames = Cryptocurrency::whereIn('crypto_id', $selectedCryptoIds)
                    ->pluck('name')
                    ->toArray();
        
                if ($availableCryptos->isEmpty()) {
                    return view('analysis.form', [
                        'cryptocurrencies' => Cryptocurrency::all(),
                        'error' => "Aucune donnée disponible pour " . implode(', ', $selectedCryptoNames) . 
                                  " entre le " . Carbon::parse($request->start_datetime)->format('d/m/Y H:i') .
                                  " et le " . Carbon::parse($request->end_datetime)->format('d/m/Y H:i'),
                        'startDate' => $request->start_datetime,
                        'endDate' => $request->end_datetime
                    ]);
                }
                if (count($availableCryptos) < count($selectedCryptoIds)) {
                    $availableCryptoIds = $availableCryptos->pluck('crypto_id')->toArray();
                    $missingCryptos = Cryptocurrency::whereIn('crypto_id', 
                        array_diff($selectedCryptoIds, $availableCryptoIds)
                    )->pluck('name')->toArray();
        
                    $warning = "Attention : Pas de données disponibles pour : " . implode(', ', $missingCryptos) .
                              " dans la période sélectionnée.";
                }
        
                $prices = $availableCryptos->pluck('current_price')->toArray();

            return view('analysis.form', [
                'cryptocurrencies' => Cryptocurrency::all(),
                'selectedCryptos' => $selectedCryptos,
                'analysisResult' => $analysisResult,
                'analysisType' => $analysisTypeDisplay,
                'startDate' => $request->start_datetime,
                'endDate' => $request->end_datetime,
                'title' => 'Analyse des Cryptomonnaies'
            ]);

        } catch (\Exception $e) {
            return view('analysis.form', [
                'cryptocurrencies' => Cryptocurrency::all(),
                'error' => 'Une erreur est survenue : ' . $e->getMessage(),
                'startDate' => $request->start_datetime,
                'endDate' => $request->end_datetime,
                'title' => 'Analyse des Cryptomonnaies'
            ]);
        }
    }


    private function calculateQuartile($array)
    {
        if (empty($array)) {
            return 0;
        }
        sort($array);
        $count = count($array);
        $position = ($count + 1) / 4;
        return round($array[floor($position) - 1], 8);
    }

    private function calculateStandardDeviation($array)
    {
        if (empty($array)) {
            return 0;
        }
        $mean = array_sum($array) / count($array);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $array)) / count($array);
        return sqrt($variance);
    }
}   