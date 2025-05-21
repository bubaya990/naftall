<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branche extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'site_id', 'parent_id'];
    protected $casts = [
        'plan_images' => 'array',
        'plan_links' => 'array'
    ];
 protected $attributes = [
        'plan_images' => '[]', // Default empty JSON array
        'plan_links' => '[]',  // Default empty JSON array
    ];
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function parent()
    {
        return $this->belongsTo(Branche::class, 'parent_id');
    }

   // Branche.php

public function children()
{
    return $this->hasMany(Branche::class, 'parent_id')->with('children'); // Recursive
}

    public function user()
{
    return $this->belongsTo(User::class,'user_id');
}
}
