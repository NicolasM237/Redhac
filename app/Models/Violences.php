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
        'situation',
        'auteurs',
        'nature_id',
        'collecte_id',
        'mode_collecte',
        'description_cas',
        'mesure_obc',
        'risque_victime',
        'attente_victime',
        'fichie1',
        'fichie2',
        'fichie3'
    ];

    // Relation avec la Nature

    public function nature()
    {
        return $this->belongsTo(Nature::class, 'nature_id');
    }

    public function collecte()
    {
        return $this->belongsTo(Collecte::class, 'collecte_id');
    }
}