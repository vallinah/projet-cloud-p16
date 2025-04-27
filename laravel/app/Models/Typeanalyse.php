<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Typeanalyse extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'type_analyse';

    // Clé primaire
    protected $primaryKey = 'id_type';
    
    public $timestamps = false;

    // Champs modifiables
    protected $fillable = [
        'type_analyse',
    ];

}