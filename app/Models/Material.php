<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    
    protected $table = 'materials';

    protected $fillable = [
        'inventory_number',
        'serial_number',
        'state',
        'room_id',
        'corridor_id',
        'materialable_type',
        'materialable_id'
    ];

    public function materialable()
    {
        return $this->morphTo();
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function corridor()
    {
      return $this->belongsTo(Corridor::class, 'corridor_id');
    }

}