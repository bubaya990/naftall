<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'reponse',
        'reclamation_id' ,
        'sender_id',
        'receiver_id',
        'seen',
    ];
    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
 