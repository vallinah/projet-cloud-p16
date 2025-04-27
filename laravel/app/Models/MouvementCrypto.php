<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementCrypto extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'mouvement_crypto';

    // Clé primaire
    protected $primaryKey = 'id_mouvement_crypto';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'cours',
        'vente',
        'achat',
        'crypto_id',
        'date_mouvement',
    ];

    public function mouvementsCrypto()
    {
        return $this->hasMany(MouvementCrypto::class, 'id_commission', 'id_commission');
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'crypto_id', 'crypto_id');
    }

}