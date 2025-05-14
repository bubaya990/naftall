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
        'ram_id', // Keeping as ram_id to match your model
    ];

    protected $appends = ['total_ram'];

    public function material()
    {
        return $this->morphOne(Material::class, 'materialable');
    }

    public function rams()
    {
        return $this->belongsTo(Ram::class, 'ram_id'); // Fixed typo from brlongto to belongsTo
    }

    public function getTotalRamAttribute()
    {
        return $this->rams ? $this->rams->capacity : null;
    }
}