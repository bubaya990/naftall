<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use App\Models\Reclamation;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Assurez-vous qu'il y a au moins deux utilisateurs
         $users = User::inRandomOrder()->take(2)->get();

         if ($users->count() < 2) {
             $this->command->info('Pas assez d\'utilisateurs pour créer des messages.');
             return;
         }
 
         $sender = $users[0];
         $receiver = $users[1];

         $reclamation = Reclamation::inRandomOrder()->first();

         if (!$reclamation) {
             $this->command->info('Aucune réclamation trouvée.');
             return;
         }
 
         Message::create([
             'message' => 'Bonjour, comment vas-tu ?',
             'reponse' => null,
             'sender_id' => $sender->id,
             'receiver_id' => $receiver->id,
             'seen' => false,
             'reclamation_id' => $reclamation->id,
         ]);
 
         Message::create([
             'message' => 'As-tu reçu mon précédent message ?',
             'reponse' => 'Oui, je l\'ai bien reçu.',
             'sender_id' => $sender->id,
             'receiver_id' => $receiver->id,
             'seen' => true,
             'reclamation_id' => $reclamation->id,
         ]);
     
    }
}