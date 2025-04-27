<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Support\Str;

class ReclamationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assurez-vous qu'il y a au moins un utilisateur
        $user = User::first();

        if (!$user) {
            $this->command->info('Aucun utilisateur trouvé. Veuillez créer un utilisateur avant d\'exécuter ce seeder.');
            return;
        }

         // Liste des états possibles pour une réclamation
         $states = ['nouvelle', 'en_cours', 'traitée'];

        Reclamation::create([
            'num_R' => 'REC-001',
            'date_R' => now()->subDays(rand(0, 30)),       // une date dans les 30 derniers jours
            'definition' => 'Problème de connexion réseau', 
            'message' => 'Impossible d\'accéder à Internet depuis le bureau.' ,
            'state' => $states[array_rand($states)],
            'user_id' => $user->id,
        ]);

        Reclamation::create([
            'num_R' => 'REC-002',
            'date_R' => now()->subDays(rand(0, 30)),       // une date dans les 30 derniers jours
            'definition' => 'Erreur d\'impression' ,
            'message' => 'L\'imprimante affiche une erreur de bourrage papier.' ,
            'state' => $states[array_rand($states)],
            'user_id' => $user->id,
        ]);
    
    }
   
}