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

        if ($computers->isEmpty()) {
            $this->command->warn("Aucun ordinateur trouvé. Exécutez d'abord le seeder des ordinateurs.");
            return;
        }

        $capacities = ['4GB', '8GB', '16GB', '32GB'];
        $states = ['good', 'bad', 'out of order'];

        // ✅ Étape 1 : S'assurer que chaque capacité est utilisée une fois
        foreach ($capacities as $capacity) {
            Ram::create([
                'capacity' => $capacity,
                'state' => $states[array_rand($states)],
                'computer_id' => $computers->random()->id,
            ]);
        }

        // ✅ Étape 2 : Ajouter des RAMs aléatoires supplémentaires
        foreach ($computers as $computer) {
            $ramCount = rand(1, 4); // 1-4 RAMs

            for ($i = 0; $i < $ramCount; $i++) {
                Ram::create([
                    'capacity' => $capacities[array_rand($capacities)],
                    'state' => $states[array_rand($states)],
                    'computer_id' => $computer->id,
                ]);
            }
        }
    }
}