<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_R',
        'date_R',
        'definition',
        'message',
        'state',
        'user_id',
        'handler_id',
        'handled_at',
        'completed_at'
    ];
    
    protected $dates = [
        'handled_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
// Add this to your Reclamation model
public function handler()
{
    return $this->belongsTo(User::class, 'handler_id');
}
}
