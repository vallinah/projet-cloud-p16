<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    protected $fillable = ['user_id', 'crypto_id', 'amount'];

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'crypto_id', 'crypto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}