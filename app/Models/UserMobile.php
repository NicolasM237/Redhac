<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserMobile extends Model
{
    use Notifiable, HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'numero',
        'otp',
    ];
    protected $hidden = ['otp'];
}