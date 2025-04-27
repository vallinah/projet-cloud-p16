<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;

class Cryptocurrency extends Model
{
    protected $table = 'cryptocurrencies';
    protected $primaryKey = 'crypto_id';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'symbol',
        'current_price',
        'created_date',
        'updated_date',
    ];

    public $timestamps = false; 

    protected static function booted()
    {
        static::created(fn($crypto) => self::syncToFirebase($crypto));
        static::updated(fn($crypto) => self::syncToFirebase($crypto));
    }

    private static function syncToFirebase($crypto)
    {
        try {
            $firebase = (new Factory)
                ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
                ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
                ->createDatabase();
            
            $firebase->getReference('cryptocurrencies/' . $crypto->crypto_id)->set([
                'name' => $crypto->name,
                'symbol' => $crypto->symbol,
                'current_price' => $crypto->current_price,
                'updated_date' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur de synchronisation avec Firebase: ' . $e->getMessage());
        }
    }


}
