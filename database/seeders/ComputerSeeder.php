<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Computer;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Ram;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = Room::first();
        if (!$room) {
            $this->command->error('No room found. Create rooms first.');
            return;
        }

        $corridor = Corridor::first();
        if (!$corridor) {
            $this->command->error('No corridor found. Create corridors first.');
            return;
        }

        $states = ['bon', 'défectueux', 'hors_service'];

        // Récupérer toutes les RAMs disponibles
        $rams = Ram::all();
        if ($rams->isEmpty()) {
            $this->command->error('No RAMs found. Create RAM entries first.');
            return;
        }

        // Créer les ordinateurs dans l'ordre désiré
        // Assigner une RAM aléatoire à chaque ordinateur
        $ram = $rams->random();  // Choisir une RAM aléatoire pour tous les ordinateurs

        // 1. Création de l'ordinateur HP
        $computer1 = Computer::create([
            'computer_brand' => 'HP',
            'computer_model' => 'ProBook 450 G7',
            'OS' => 'Windows10',
            'ram_id' => $ram->id
        ]);
        
        $computer1->material()->create([
            'inventory_number' => 'INV-COMP-001',
            'serial_number' => 'SN-HP-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
        ]);

        // 2. Création de l'ordinateur Dell
        $computer2 = Computer::create([
            'computer_brand' => 'Dell',
            'computer_model' => 'Optiplex 7070',
            'OS' => 'Windows 10',
            'ram_id' => $ram->id
        ]);
        
        $computer2->material()->create([
            'inventory_number' => 'INV-COMP-002',
            'serial_number' => 'SN-DELL-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
        ]);

        // 3. Création de l'ordinateur Alfatron
        $computer3 = Computer::create([
            'computer_brand' => 'Alfatron',
            'computer_model' => 'Alfatron A7',
            'OS' => 'Windows 10',
            'ram_id' => $ram->id
        ]);
        
        $computer3->material()->create([
            'inventory_number' => 'INV-COMP-003',
            'serial_number' => 'SN-ALF-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
        ]);

        // 4. Création de l'ordinateur Fujitsu
        $computer4 = Computer::create([
            'computer_brand' => 'Fujitsu',
            'computer_model' => 'Lifebook A555',
            'OS' => 'Windows 10',
            'ram_id' => $ram->id
        ]);
        
        $computer4->material()->create([
            'inventory_number' => 'INV-COMP-004',
            'serial_number' => 'SN-FUJ-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
        ]);
    }
}