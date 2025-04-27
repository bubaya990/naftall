<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Printer;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Material;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la première salle et corridor disponibles
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

        // États possibles pour les imprimantes
        $states = ['bon', 'défectueux', 'hors_service'];

        // Créer des imprimantes et leur matériel

        // Imprimante 1
        $printer1 = Printer::create([
            'printer_brand' => 'Kyocera',
            'printer_model' => 'TASKalfa 2553ci',
        ]);
        
        $printer1->material()->create([
            'inventory_number' => 'INV-PRT-001',
            'serial_number' => 'SN-KYO-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
            'materialable_type' => 'App\Models\Printer',
            'materialable_id' => $printer1->id
        ]);

        // Imprimante 2
        $printer2 = Printer::create([
            'printer_brand' => 'Canon',
            'printer_model' => 'imageRUNNER 2525',
        ]);
        
        $printer2->material()->create([
            'inventory_number' => 'INV-PRT-002',
            'serial_number' => 'SN-CAN-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
            'materialable_type' => 'App\Models\Printer',
            'materialable_id' => $printer2->id
        ]);

        // Imprimante 3
        $printer3 = Printer::create([
            'printer_brand' => 'Epson',
            'printer_model' => 'EcoTank ET-2720',
        ]);
        
        $printer3->material()->create([
            'inventory_number' => 'INV-PRT-003',
            'serial_number' => 'SN-EPS-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
            'materialable_type' => 'App\Models\Printer',
            'materialable_id' => $printer3->id
        ]);

        // Imprimante 4
        $printer4 = Printer::create([
            'printer_brand' => 'HP',
            'printer_model' => 'LaserJet Pro M404dn',
        ]);
        
        $printer4->material()->create([
            'inventory_number' => 'INV-PRT-004',
            'serial_number' => 'SN-HP-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id,
            'materialable_type' => 'App\Models\Printer',
            'materialable_id' => $printer4->id
        ]);
    }
}
