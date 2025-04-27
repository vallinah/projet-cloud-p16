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

class HistoryController extends Controller
{

    public function index()
    {
        $title = "Historique operations";
        $history = MouvementCrypto::all();
        return view('admin.index', compact('title', 'history'));
    }

    public function analyse(Request $request)
    {
        $title = "Historique des opérations";

        $startDate = $request->input('start_datetime');
        $startTime = $request->input('start_time');
        if ($startDate && $startTime) {
            $startDatetime = $startDate . ' ' . $startTime;
        } else {
            $startDatetime = null;
        }

        $query = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->select('mouvement_crypto.*', 'cryptocurrencies.name as crypto_name');

        if ($startDatetime) {
            $query->where('date_mouvement', '==', $startDatetime);
        }
        $history = $query->get();

        return view('admin.index', compact('title', 'history'));
    }


    public function showUserHistory($userId)
    {
        $title = "Historique de l'utilisateur";
        $history = DB::table('mouvement_crypto')
            ->join('cryptocurrencies', 'mouvement_crypto.crypto_id', '=', 'cryptocurrencies.crypto_id')
            ->select('mouvement_crypto.*', 'cryptocurrencies.name as crypto_name')
            ->where('user_id', $userId)
            ->get();

        return view('admin.history', compact('title', 'history', 'userId'));
    }

}