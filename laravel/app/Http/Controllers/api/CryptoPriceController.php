<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;

class CryptoPriceController extends Controller
{
    public function getCurrentPrices()
    {
        return Cryptocurrency::select(
            'crypto_id',
            'name',
            'symbol',
            'current_price',
            'updated_date'
        )->orderBy('current_price', 'desc')->get();
    }
}
    