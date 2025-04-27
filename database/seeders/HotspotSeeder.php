<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotspot;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Material;

class HotspotSeeder extends Seeder
{
    /**Run the database seeds.*/
  public function run(): void{$room = Room::first();
      if (!$room) {$this->command->error('No room found. Create rooms first.');
          return;}

        $corridor = Corridor::first();
        if (!$corridor) {
            $this->command->error('No corridor found. Create corridors first.');
            return;
        }

        $states = ['bon', 'défectueux', 'hors_service'];

        // Create hotspot with its material
        $hotspot = Hotspot::create([
            'password' => 'Hotspot@2024',
        ]);

        $hotspot->material()->create([
            'inventory_number' => 'INV-HOTSPOT-001',
            'serial_number' => 'SN-HOTSPOT-001',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id
        ]);

        // You can add more hotspots if needed following the same pattern
        $hotspot2 = Hotspot::create([
            'password' => 'Guest@2024',
        ]);

        $hotspot2->material()->create([
            'inventory_number' => 'INV-HOTSPOT-002',
            'serial_number' => 'SN-HOTSPOT-002',
            'state' => $states[array_rand($states)],
            'room_id' => $room->id,
            'corridor_id' => $corridor->id
        ]);
    }
}