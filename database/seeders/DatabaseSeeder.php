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
        User::firstOrCreate(
            ['email' => 'admin@redhac.com'],
            [
                'nom' => 'Mekam',
                'prenom' => 'Nicolas',
                'telephone' => '670000000',
                'adresse' => 'Douala',
                'profil' => 'Administrateur',
                'password' => Hash::make('password123')
            ]
        );
    }
}
