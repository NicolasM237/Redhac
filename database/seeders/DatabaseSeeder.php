<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ton compte Administrateur Principal
        User::firstOrCreate(
            ['email' => 'nicolas@redhac.com'],
            [
                'nom' => 'Mekam',
                'prenom' => 'Nicolas',
                'telephone' => '670000000',
                'adresse' => 'Douala',
                'profil' => 'Administrateur',
                'password' => Hash::make('Jeanne237.com')
            ]
        );

        // 2. Ajout d'un second utilisateur (ex: un compte de test ou un collègue)
        User::firstOrCreate(
            ['email' => 'fotso@redhac.com'],
            [
                'nom' => 'Mr Fotso',
                'prenom' => 'Narcise',
                'telephone' => '690000000',
                'adresse' => 'Douala',
                'profil' => 'Administrateur', // Ou 'Administrateur' selon ton besoin
                'password' => Hash::make('fotso237.com')
            ]
        );
    }
}