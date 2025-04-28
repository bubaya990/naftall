<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];
    
    public function locations()
    {
        return $this->hasMany(Location::class, 'site_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
    public function branches()
{
    return $this->hasMany(Branche::class);
}

}