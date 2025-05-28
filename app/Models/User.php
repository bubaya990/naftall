<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branche_id',
        'site_id',
        
    'profile_picture',

    ];

  
    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }
    
    

    public function setEmailAttribute($value)
{
    $this->attributes['email'] = strtolower($value);
}

    public function branche()
    {
        return $this->belongsTo(Branche::class, 'branche_id');

    }



public function messages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}

public function unreadMessages()
{
    return $this->hasMany(Message::class, 'receiver_id')->where('seen', false);
}
}           