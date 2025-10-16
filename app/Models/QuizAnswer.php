<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
        'quiz_id',         // new
        'user_id',         // new
        'selected_option', // new
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'selected_option' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    // Optional relation to quiz
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Optional relation to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
