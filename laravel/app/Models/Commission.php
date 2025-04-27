<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Commission extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'commission';

    // Clé primaire
    protected $primaryKey = 'id_commission';
    
    public $timestamps = false;

    // Champs modifiables
    protected $fillable = [
        'valeur',
        'date_commission',
    ];

    public static function getFirst()
    {
        return self::first();
    }

}