<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\Branche;

class BrancheSeeder extends Seeder
{
    public function run()
    {
        // Find sites
        $siege = Site::where('name', 'Siege')->first();
        $chiffa = Site::where('name', 'Chiffa')->first();
        $ainOussara = Site::where('name', 'Ain-Oussara')->first();
        $djelfa = Site::where('name', 'Djelfa')->first();

        // Create Branches for Siege
        $siegeCommercial = Branche::create([
            'name' => 'Commercial',
            'site_id' => $siege->id,
            'parent_id' => null,
        ]);

        $siegeCarburant = Branche::create([
            'name' => 'Carburant',
            'site_id' => $siege->id,
            'parent_id' => null,
        ]);

        // Under Commercial -> Agence
        $agence = Branche::create([
            'name' => 'Agence',
            'site_id' => $siege->id,
            'parent_id' => $siegeCommercial->id,
        ]);

        // Under Agence -> GD
        $gd = Branche::create([
            'name' => 'GD',
            'site_id' => $siege->id,
            'parent_id' => $agence->id,
        ]);

        // Stations under GD
        for ($i = 1; $i <= 11; $i++) {
            Branche::create([
                'name' => 'Station ' . $i,
                'site_id' => $siege->id,
                'parent_id' => $gd->id,
            ]);
        }

        // Create Branches for Chiffa
        $chiffaCommercial = Branche::create([
            'name' => 'Commercial',
            'site_id' => $chiffa->id,
            'parent_id' => null,
        ]);

        $chiffaCarburant = Branche::create([
            'name' => 'Carburant',
            'site_id' => $chiffa->id,
            'parent_id' => null,
        ]);

        // Under Chiffa Commercial -> LP and CDD
        Branche::create([
            'name' => 'LP',
            'site_id' => $chiffa->id,
            'parent_id' => $chiffaCommercial->id,
        ]);

        Branche::create([
            'name' => 'CDD',
            'site_id' => $chiffa->id,
            'parent_id' => $chiffaCommercial->id,
        ]);

        // Create Branches for Ain-Oussara
        Branche::create([
            'name' => 'Commercial',
            'site_id' => $ainOussara->id,
            'parent_id' => null,
        ]);

        Branche::create([
            'name' => 'Carburant',
            'site_id' => $ainOussara->id,
            'parent_id' => null,
        ]);

        // Create Branches for Djelfa
        Branche::create([
            'name' => 'Commercial',
            'site_id' => $djelfa->id,
            'parent_id' => null,
        ]);

        Branche::create([
            'name' => 'Carburant',
            'site_id' => $djelfa->id,
            'parent_id' => null,
        ]);
    }
}
