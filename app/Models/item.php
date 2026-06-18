<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'image',
        'category',
        'time',
        'user_id'
    ];
}
