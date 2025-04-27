<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Corridor;
use Illuminate\Support\Facades\DB;

class CorridorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('corridors')->insert([
            ['location_id' => 1],
            ['location_id' => 2],
            ['location_id' =>3],

        ]);
}
}
