<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'site_id',
        'floor_id',
];

public function floor()
{
    return $this->belongsTo(Floor::class, 'floor_id');
}
public function site()
{
    return $this->belongsTo(Site::class, 'site_id');
}
public function corridors()
{
    return $this->hasMany(Corridor::class);
}

public function rooms()
{
    return $this->hasMany(Room::class);
}
public static function getTypes()
{
    return ['Poste police', 'Rez-de-chaussee', 'Étage'];
}



}
