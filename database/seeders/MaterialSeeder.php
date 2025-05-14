<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Computer;
use App\Models\Printer;
use App\Models\IpPhone;
use App\Models\Hotspot;

class MaterialSeeder extends Seeder
{
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

        // Create computer with material

        // Create printer with material
        $printer = Printer::create([
            'printer_brand' => 'HP',
            'printer_model' => 'LaserJet Pro'
        ]);
        
        $printer->material()->create([
            'inventory_number' => 'INV-1002',
            'serial_number' => 'SN-XYZ-002',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id
        ]);

        // Create IP phone with material
        $ipPhone = IpPhone::create([
            'mac_number' => '00:1A:2B:3C:4D:5E'
        ]);
        
        $ipPhone->material()->create([
            'inventory_number' => 'INV-1003',
            'serial_number' => 'SN-XYZ-003',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id
        ]);

        // Create hotspot with material
        $hotspot = Hotspot::create([
            'password' => 'secure123'
        ]);
        
        $hotspot->material()->create([
            'inventory_number' => 'INV-1004',
            'serial_number' => 'SN-XYZ-004',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id
        ]);
    }
}