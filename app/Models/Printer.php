<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
    'printer_brand',
    'printer_model',
  
];

public function material()
{
    return $this->morphOne(Material::class, 'materialable');
}


 

}
