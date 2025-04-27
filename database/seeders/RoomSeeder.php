<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Location;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 👇 Récupère la première location existante
        $location = Location::first();

        if (!$location) {
            $this->command->error('Aucune location trouvée. Crée d’abord des locations.');
            return;
        }

        // ✅ Tableau bien structuré
        $rooms = [
            [
                'code' => 'INF-101',
                'name' => 'Salle Informatique',
                'type' => 'Salle Réseau',
                'location_id' => $location->id,
            ],
            [
                'code' => 'REU-202',
                'name' => 'Salle des reunions',
                'type' => 'Salle réunion',
                'location_id' => $location->id,
            ],
            [
                'code' => 'REU-203',
                'name' => 'Salle de Directeur',
                'type' => 'Bureau',
                'location_id' => $location->id,
            ],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['code' => $room['code']], $room);
        }
    }
}
