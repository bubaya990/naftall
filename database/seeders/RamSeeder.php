<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ram;
use App\Models\Computer;

class RamSeeder extends Seeder
{
    public function run(): void
    {
        $computers = Computer::all();
        $capacities = ['4GB', '8GB', '16GB', '32GB'];
        $states = ['good', 'bad', 'out of order'];

        foreach ($computers as $computer) {
            $ramCount = rand(1, 4); // 1-4 RAM sticks per computer

            for ($i = 0; $i < $ramCount; $i++) {
                Ram::create([
                    'capacity' => $capacities[array_rand($capacities)],
                    'state' => $states[array_rand($states)],
                    'computer_id' => $computer->id
                ]);
            }
        }
    }
}