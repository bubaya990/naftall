<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branche;
use App\Models\Site;


class BrancheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $sites = Site::all();
        foreach ($sites as $site) {
        Branche::firstOrCreate([
            'name' => 'carburant',
            'site_id' => $site->id,
        ]);
    
        Branche::firstOrCreate([
            'name' => 'commercial',
            'site_id' => $site->id,
        ]);
    }
}
}
