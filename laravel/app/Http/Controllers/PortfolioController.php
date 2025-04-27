<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase\Factory;
class PortfolioController extends Controller
{
    protected $database;

    public function __construct()
    {
        // Initialisation de Firebase
        $this->database = (new Factory)
            ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
            ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
            ->createDatabase();
    }


    public function getPortfolioData()
    {
        // Récupérer les données du portefeuille de l'utilisateur
        $wallets = CryptoWallet::with('cryptocurrency')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($wallet) {
                return [
                    'crypto_name' => $wallet->cryptocurrency->name,
                    'symbol' => $wallet->cryptocurrency->symbol,
                    'amount' => number_format($wallet->amount, 8),
                    'current_price' => number_format($wallet->cryptocurrency->current_price, 2),
                    'total_value' => number_format($wallet->amount * $wallet->cryptocurrency->current_price, 2),
                ];
            });
    
        // Calculer la valeur totale du portefeuille
        $total_portfolio_value = $wallets->sum(function ($wallet) {
            return str_replace(',', '', $wallet['total_value']); 
        });
    
        // Insérer ou mettre à jour les données dans Firebase
        $this->insertPortfolioToFirebase(Auth::id(), $wallets, $total_portfolio_value);
    
        // Retourner les données au format JSON pour l'affichage
        return response()->json([
            'wallets' => $wallets,
            'total_portfolio_value' => number_format($total_portfolio_value, 2),
        ]);
    }   
    
    
    public function index()
    {
        $title = "Mon Portfolio"; 

        $wallets = CryptoWallet::with('cryptocurrency')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($wallet) {
                return [
                    'crypto_id' => $wallet->cryptocurrency->crypto_id,
                    'crypto_name' => $wallet->cryptocurrency->name,
                    'symbol' => $wallet->cryptocurrency->symbol,
                    'amount' => $wallet->amount,
                    'current_price' => $wallet->cryptocurrency->current_price,
                    'total_value' => $wallet->amount * $wallet->cryptocurrency->current_price
                ];
            });

        $total_portfolio_value = $wallets->sum('total_value');

        $this->insertPortfolioToFirebase(Auth::id(), $wallets, $total_portfolio_value);

        return view('user.portfolio.index', compact('title','wallets', 'total_portfolio_value'));
    }
    
    public function insertPortfolioToFirebase($userId, $wallets, $totalPortfolioValue)
    {
        $portfolioData = [
            'user_id' => $userId,
            'wallets' => $wallets->toArray(),
            'total_portfolio_value' => $totalPortfolioValue,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->database->getReference('portfolios/' . $userId)
            ->set($portfolioData);
    }

    
    public function filtre()
    {
        $title='filtre';
        return view('user.portfolio.filtre', compact('title'));
    }


    public function filtreuser(Request $request)
    {
        $request->validate([
            'max_datetime' => 'nullable|date', 
        ]);
    
        $startDatetime = $request->input('max_datetime');
        $wallets = CryptoWallet::with('cryptocurrency')
            ->get()
            ->groupBy('user_id') 
            ->map(function ($walletsByUser) {
                return [
                    'user_id' => $walletsByUser->first()->user_id,
                    'total_portfolio_value' => $walletsByUser->sum(function ($wallet) {
                        return $wallet->amount * $wallet->cryptocurrency->current_price;
                    })
                ];
            })
            ->values();
        $query = DB::table('v_mouvement_commission');
    
        if ($startDatetime) {
            $query->where('date_mouvement', '<=', $startDatetime);
        }
    
        $results = $query
            ->groupBy('user_id')
            ->select(
                'user_id',
                DB::raw('SUM(CASE WHEN achat IS NOT NULL THEN achat ELSE 0 END) AS total_achat'),
                DB::raw('SUM(CASE WHEN vente IS NOT NULL THEN vente ELSE 0 END) AS total_vente')
            )
            ->get()
            ->map(function ($result) use ($wallets) {
                $userWallet = collect($wallets)->firstWhere('user_id', $result->user_id);
                $result->portefeuille = $userWallet ? $userWallet['total_portfolio_value'] : 0;
                return $result;
            });
        return view('user.portfolio.resultfiltre', [
            'title' => 'Résultat filtre',
            'results' => $results,
        ]);
    }
      

}