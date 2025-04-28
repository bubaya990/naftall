<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;

class SiteSeeder extends Seeder
{
    public function run()
    {
        Site::create(['name' => 'Siege']);
        Site::create(['name' => 'Chiffa']);
        Site::create(['name' => 'Ain-Oussara']);
        Site::create(['name' => 'Djelfa']);
    }
}
