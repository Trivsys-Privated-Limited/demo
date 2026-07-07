<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'is_read',
    ];

    // Message kisne bheja
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Message kisko bheja gaya
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
