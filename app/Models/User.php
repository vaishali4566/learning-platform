<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; // ✅ Add this

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable; // ✅ Add Notifiable here

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_image',
        'city',
        'country',
        'email_verified_at',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
