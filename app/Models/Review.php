<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Ye line paste karein
    protected $fillable = ['table_id', 'guest_id', 'rating', 'comment'];
}
