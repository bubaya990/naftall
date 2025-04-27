<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('floors')->insert([
            ['floor_number' => 0], // Rez-de-chaussée
            ['floor_number' => 1], // Premier étage
            ['floor_number' => 2], // Deuxième étage
            ['floor_number' => 3], // Troisième étage

        ]);
    }
}
