<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'parent_id',
        'bussiness_name',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // All subscriptions of this user
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id')->orderByDesc('start_date');
    }

    // Latest active (non-expired) subscription
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'user_id')
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->latest('end_date');
    }

    // Parent restaurant admin (if this is restaurant_user)
    public function parentAdmin()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Child users (if this is restaurant_admin)
    public function childUsers()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // All restaurants under super admin
    public function restaurants()
    {
        if ($this->isSuperAdmin()) {
            return User::where('role', 'restaurant_admin')->orderBy('bussiness_name');
        }
        return User::whereNull('id'); // Empty query
    }

    // Role checking methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isRestaurantAdmin(): bool
    {
        return $this->role === 'restaurant_admin';
    }

    public function isRestaurantUser(): bool
    {
        return $this->role === 'restaurant_user';
    }
}
