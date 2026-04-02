<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    use HasFactory;

    protected $table = 'collectes';

    protected $fillable = [
        'nature_id',
        'nom',
        //'quantite',
        'date_collecte'
    ];

    // Relation avec Nature
    public function nature()
    {
        return $this->belongsTo(Nature::class);
    }
}
