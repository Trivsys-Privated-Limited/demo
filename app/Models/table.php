<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class table extends Model
{
    protected $fillable = [
        'table_number',
        'qr_token',
        'user_id'
    ];
}
