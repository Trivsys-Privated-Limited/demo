<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'start_date',
        'end_date',
        'months',
        'amount',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'months'     => 'integer',
        'amount'     => 'decimal:2',
    ];

    // Restaurant user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin who created it
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Check if this subscription is expired
    public function isExpired(): bool
    {
        return Carbon::today()->gt($this->end_date);
    }

    // How many days remaining
    public function daysRemaining(): int
    {
        if ($this->isExpired()) return 0;
        return Carbon::today()->diffInDays($this->end_date);
    }

    // Human-readable status with days left
    public function statusLabel(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }
        $days = $this->daysRemaining();
        if ($days === 0) return 'Expires Today';
        return $days . ' days left';
    }
}
