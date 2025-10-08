<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'receipt_url',
    ];

    protected $casts = [
        'status' => 'string',
        'payment_method' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->hasOne(Purchase::class);
    }
}