<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = [
        'computer_brand',
        'computer_model',
        'OS',
        'ram_id', 
    ];

    protected $appends = ['total_ram'];

    public function material()
    {
        return $this->morphOne(Material::class, 'materialable');
    }

    public function rams()
    {
        return $this->belongsTo(Ram::class, 'ram_id'); 
    }

    public function getTotalRamAttribute()
    {
        return $this->rams ? $this->rams->capacity : null;
    }
}