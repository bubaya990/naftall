<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branche extends Model
{
    use HasFactory;

    protected $fillable = [
    'site_id',
    'name',
     ];

     
    public function site()
{
    return $this->belongsTo(Site::class, 'site_id');
}
public function user()
{
    return $this->belongsTo(User::class,'user_id');
}

}
