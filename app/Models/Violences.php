<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violences extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'status',
        'contact',
        'occupation',
        'age',
        'sexe',
        'nationalite',
        'residence',
        'datesurvenue',
        'lieusurvenue',
        'coordinates',
        'situation',
        'auteurs',
        'user_id',
        'nature_id',
        'collecte_id',
        'description_cas',
        'mesure_obc',
        'risque_victime',
        'attente_victime',
        'fichier1',
        'fichier2',
        'fichier3'
    ];

    // Relation avec la Nature
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function nature()
    {
        return $this->belongsTo(Nature::class, 'nature_id');
    }

    public function collecte()
    {
        return $this->belongsTo(Collecte::class, 'collecte_id');
    }
}
