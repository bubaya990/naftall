<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    // Spécifie les champs que tu peux massivement affecter (fillables)
    protected $fillable = [
        'name',
        'code',
        'type',
        'location_id'
    ];
     // Définir la relation avec les autres modèles (Printers, Computers, IPPhones)
     public function materials()
     {
         return $this->hasMany(Material::class, 'room_id');
     }

     public function location()
     {
         return $this->belongsTo(Location::class, 'location_id');
     }
}
