<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ram;
use App\Models\Computer;

class RamSeeder extends Seeder
{
    public function run(): void
    {
        Ram::create(['capacity' => '4GB']);
        Ram::create(['capacity' => '8GB']);
        Ram::create(['capacity' => '16GB']);
        Ram::create(['capacity' => '32GB']);
        
    }
    
}