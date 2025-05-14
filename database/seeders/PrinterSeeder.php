<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Printer;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean reset
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing data more thoroughly
        Printer::truncate();
        Material::where('materialable_type', 'App\Models\Printer')->delete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Get the first available room and corridor
        $room = Room::first();
        if (!$room) {
            $this->command->error('No room found. Please create rooms first.');
            return;
        }

        $corridor = Corridor::first();
        if (!$corridor) {
            $this->command->error('No corridor found. Please create corridors first.');
            return;
        }

        // Possible printer states
        $states = ['bon', 'défectueux', 'hors_service'];

        // Printer data array for cleaner code
        $printers = [
            [
                'brand' => 'Kyocera',
                'model' => 'TASKalfa 2553ci',
                'inventory' => 'INV-PRT-001',
                'serial' => 'SN-KYO-001'
            ],
            [
                'brand' => 'Canon',
                'model' => 'imageRUNNER 2525',
                'inventory' => 'INV-PRT-002',
                'serial' => 'SN-CAN-001'
            ],
            [
                'brand' => 'Epson',
                'model' => 'EcoTank ET-2720',
                'inventory' => 'INV-PRT-003',
                'serial' => 'SN-EPS-001'
            ],
            [
                'brand' => 'HP',
                'model' => 'LaserJet Pro M404dn',
                'inventory' => 'INV-PRT-004',
                'serial' => 'SN-HP-001'
            ]
        ];

        foreach ($printers as $printerData) {
            try {
                $printer = Printer::create([
                    'printer_brand' => $printerData['brand'],
                    'printer_model' => $printerData['model'],
                ]);
                
                $printer->material()->create([
                    'inventory_number' => $printerData['inventory'],
                    'serial_number' => $printerData['serial'],
                    'state' => $states[array_rand($states)],
                    'room_id' => $room->id,
                    'corridor_id' => $corridor->id,
                ]);

                $this->command->info("Created printer: {$printerData['brand']} {$printerData['model']}");
            } catch (\Exception $e) {
                $this->command->error("Failed to create printer {$printerData['brand']}: ".$e->getMessage());
            }
        }

        $this->command->info('Printer seeding completed!');
    }
}