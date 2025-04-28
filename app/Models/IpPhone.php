<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'mac_number',
        
      
];

public function material()
{
    return $this->morphOne(Material::class, 'materialable');
}



    // Définir la relation avec le modèle Room
    


}
