<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpPhone;
use App\Models\Room;
use App\Models\Corridor;
use App\Models\Material;

class IpPhoneSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Récupérer une salle et un couloir
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

        // Définir les états possibles des appareils
        $states = ['bon', 'défectueux', 'hors_service'];

        // Créer le premier IP phone avec son matériel
        $macAddress1 = '00:1A:2B:3C:4D:5E';
        if (IpPhone::where('mac_number', $macAddress1)->exists()) {
            $this->command->info("MAC Address $macAddress1 already exists. Skipping creation.");
        } else {
            $ipPhone = IpPhone::create([
                'mac_number' => $macAddress1
            ]);

            $ipPhone->material()->create([
                'inventory_number' => 'INV-IP-001',
                'serial_number' => 'SN-IP-001',
                'state' => $states[array_rand($states)],
                'room_id' => $room->id,
                'corridor_id' => $corridor->id,
                'materialable_type' => 'App\Models\IpPhone',
                'materialable_id' => $ipPhone->id
            ]);
        }

        // Créer le deuxième IP phone avec son matériel
        $macAddress2 = '00:1A:2B:3C:4D:5F';
        if (IpPhone::where('mac_number', $macAddress2)->exists()) {
            $this->command->info("MAC Address $macAddress2 already exists. Skipping creation.");
        } else {
            $ipPhone2 = IpPhone::create([
                'mac_number' => $macAddress2
            ]);

            $ipPhone2->material()->create([
                'inventory_number' => 'INV-IP-002',
                'serial_number' => 'SN-IP-002',
                'state' => $states[array_rand($states)],
                'room_id' => $room->id,
                'corridor_id' => $corridor->id,
                'materialable_type' => 'App\Models\IpPhone',
                'materialable_id' => $ipPhone2->id
            ]);
        }
    }
}