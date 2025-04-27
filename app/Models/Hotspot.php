<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


    class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
    
    'password',
    
];

public function material()
{
    return $this->morphOne(Material::class, 'materialable');
}



}
