<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementFond extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'mouvement_fond';

    // Clé primaire
    protected $primaryKey = 'id_mouvement_fond';

    public $timestamps = false;

    // Champs modifiables
    protected $fillable = [
        'montant_retrait',
        'date_mouvement_fond',
        'montant_depot',
        'user_id',
        'is_valid',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}