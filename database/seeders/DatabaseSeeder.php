<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Nature;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        // 1️⃣ Compte Administrateur Principal
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

        // 2️⃣ Second utilisateur
        User::firstOrCreate(
            ['email' => 'fotso@redhac.com'],
            [
                'nom' => 'Mr Fotso',
                'prenom' => 'Narcise',
                'telephone' => '690000000',
                'adresse' => 'Douala',
                'profil' => 'Administrateur',
                'password' => Hash::make('fotso237.com')
            ]
        );

        // 3️⃣ Utilisateur mobile
        User::firstOrCreate([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'telephone' => '0102030405',
            'email' => 'jean.mobile@example.com',
            'adresse' => '123 Rue de la App',
            'profil' => 'client',
            'otp' => null,
            'active' => true,
            'type' => 'mobile',
            'password' => Hash::make('password123'),
        ]);

        // 4️⃣ Insertion des natures de cas
        $natures = [
            'Violation des libertés fondamentales',
            'Violations d’autres droits civils et politiques',
            'Menaces et intimidations sur les défenseurs des droits humains',
            'Les représailles l’encontre des DDH',
            'Violation des droits humains liés à la COVID-19',
            'Atteintes à la liberté d’expression',
            'Violations des droits des minorités',
            'Harcèlement et discrimination institutionnelle',
            'Atteintes aux droits économiques, sociaux et culturels',
            'Restrictions illégales aux activités des ONG'
        ];

        foreach ($natures as $nature) {
            Nature::firstOrCreate(
                ['nom' => $nature],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}