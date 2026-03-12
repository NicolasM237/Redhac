<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les champs autorisés à être remplis
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'type',
        'otp',
        'email',
        'adresse',
        'profil',
        'password'
    ];

    /**
     * Les champs cachés lors de la sérialisation
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversion de types
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}