<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Site; 
use App\Models\Branch;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    


       // Exécution des autres seeders
       $this->call([
        SiteSeeder::class,
        BrancheSeeder::class,
        UserSeeder::class, // Doit être avant Message/Reclamation
        FloorSeeder::class,
        LocationSeeder::class,
        CorridorSeeder::class, // Doit être avant Material
        RoomSeeder::class,
      
        MaterialSeeder::class, // Doit être avant Printer/Computer
        RamSeeder::class,
        ComputerSeeder::class,
       
        PrinterSeeder::class,
        IpPhoneSeeder::class,
        HotspotSeeder::class,
        ReclamationSeeder::class,
        MessageSeeder::class,
        
    ]);
}
}


