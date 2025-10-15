<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'marks',
        'options',        // add this
        'correct_option', // add this
    ];

    // Automatically cast JSON and integer
    protected $casts = [
        'options' => 'array',
        'correct_option' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Optional: you can keep answers() if you still use the quiz_answers table
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
