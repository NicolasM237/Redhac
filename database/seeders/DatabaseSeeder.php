<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Nature;
use App\Models\Collecte; 
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

        // Comptes Administrateurs et Utilisateurs
        User::firstOrCreate(
            ['email' => 'nicolas@redhac.com'],
            [
                'nom' => 'Ing Mekam',
                'prenom' => 'Nicolas',
                'telephone' => '670000000',
                'adresse' => 'Douala',
                'profil' => 'Administrateur',
                'password' => Hash::make('Jeanne237.com')
            ]
        );

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

        User::firstOrCreate(
            ['email' => 'jean.mobile@example.com'],
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'telephone' => '0102030405',
                'adresse' => '123 Rue de la App',
                'profil' => 'client',
                'otp' => null,
                'active' => true,
                'type' => 'mobile',
                'password' => Hash::make('password123'),
            ]
        );

        //Insertion des natures de cas
        $natures = [
            'Violation des libertés fondamentales',
            'Violations d’autres droits civils et politiques',
            'Menaces et intimidations sur les défenseurs des droits humains',
            'Les représailles l’encontre des DDH',
            'Violation des droits humains liés à la COVID-19',
            'Atteintes à la liberté d’expression',
            'Violations des droits des minorités',
            'Harcèlement et discrimination institutionnelle',
            'Atteintes aux droits économiques, sociaux et culturels',
            'Restrictions illégales aux activités des ONG'
        ];

        foreach ($natures as $natureNom) {
            Nature::firstOrCreate(['nom' => $natureNom]);
        }
        // Insertion des méthodes de collecte
        $defaultNature = Nature::first();

        $methodesCollecte = [
            'Témoignage de la victime',
            'Témoignage du témoin',
            'Réseaux sociaux',
            'Email'
        ];

        foreach ($methodesCollecte as $methode) {
            Collecte::firstOrCreate(
                ['nom' => $methode],
                [
                    'nature_id' => $defaultNature->id,
                    'quantite' => 10, 
                    'date_collecte' => $now->toDateString(),
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }
    }
}