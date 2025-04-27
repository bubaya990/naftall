<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { Location::create([
        'type' => 'Poste police',
        'floor_id' => 1,
        'site_id' => 1,
    ]);

    Location::create([
        'type' => 'Rez-de-chaussee',
        'floor_id' => 2,
        'site_id' => 1,
    ]);

    Location::create([
        'type' => 'Étage',
        'floor_id' => 3,
        'site_id' => 1,
    ]);
    }
}
