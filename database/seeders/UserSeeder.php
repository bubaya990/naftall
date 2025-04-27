<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Site; 
use App\Models\Branch;

class UserSeeder extends Seeder
{
    /**Run the database seeds.*/
  public function run(): void{

    // Créer un utilisateur par branche pour chaque site
    $sites = Site::with('branches')->get();

    foreach ($sites as $site) {
        foreach ($site->branches as $branche) {
            // Créer un utilisateur unique pour chaque combinaison site/branche


    // Admin 
    User::updateOrCreate(
        ['email' => "admin@example.com"], // condition de recherche
        [
            'name' => 'Admin User',
            'password' => Hash::make('adminpassword'),
            'role' => 'admin',
            'site_id' => $site->id,
            'branche_id' => $branche->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

        // Utilisateur (Employee) User
        User::updateOrCreate(
            [
                'email' => "utilisateur@example.com", // Unique
                ],  //Email Dynamique,Dépend des IDs site/branche
            [
            'name' => 'Utilisateur User',
            'password' => Hash::make('utilisateurpassword'),
            'role' => 'utilisateur',
            'site_id'=>$site->id,
            'branche_id' => $branche->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // SuperAdmin User
        User::updateOrCreate(
           
            [
                'email' => "superadmin@example.com", // Unique
            ],//Email Dynamique,Dépend des IDs site/branche
            [
            'name' => 'SuperAdmin User',
            'password' => Hash::make('superadminpassword'),
            'role' => 'superadmin',
            'site_id'=>$site->id,
            'branche_id' => $branche->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Leader User
        User::updateOrCreate(
            [
                'email' => "leader@example.com", // Unique
            ],//Email Dynamique,Dépend des IDs site/branche
            [
         'name' => 'Leader User',
            'password' => Hash::make('leaderpassword'),
            'role' => 'leader',
            'site_id'=>$site->id,
            'branche_id' => $branche->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    }
}
}