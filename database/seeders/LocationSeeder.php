<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Floor;



class LocationSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    { Location::create([
        'name'=>'Poste police' ,
        'type' => 'Poste police',
        'floor_id' => 1,
        'site_id' => 1,
    ]);

    Location::create([
        'name'=>'Rez-de-chausse siege',
        'type' => 'Rez-de-chaussee',
        'floor_id' => 2,
        'site_id' => 1,
    ]);

  /*
    $floors = Floor::all();

foreach ($floors as $floor) {
    Location::create([
        'name' => 'Étage ' . $floor->number,
        'type' => 'Étage',
        'floor_id' => $floor->id,
        'site_id' => 1,
    ]);
}*/
$floor = Floor::find(3);

Location::create([
    'name' => 'Étage ' . $floor->number,
    'type' => 'Étage',
    'floor_id' => 1,
    'site_id' => 1,
]);
}
}
