<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Nature extends Model
{
    use HasFactory, Notifiable;

    /**
     * Les champs autorisés à être remplis
     */
    protected $fillable = [
        'nom',
    ];
}
