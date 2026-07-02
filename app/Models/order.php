<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'guest_id',
        'item_id',
        'quantity',
        'total',
        'note',
        'order_number',
        'status',
        'served_at',
        'delivered_at',
        'preparation_minutes',
    ];

    protected $casts = [
        'served_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(table::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function item()
    {
        return $this->belongsTo(item::class);
    }
}
