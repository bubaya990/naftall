<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\Branche;



class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
        public function run(): void
    {
        $siteNames = ['Siege', 'Chiffa', 'Djelfa', 'Ain Oussara'];

        foreach ($siteNames as $name) {
            $site = Site::create(['name' => $name]);

            // Ajout des deux branches pour chaque site
            $site->branches()->createMany([
                ['name' => 'carburant'],
                ['name' => 'commercial'],
            ]);
        }
    }
}

