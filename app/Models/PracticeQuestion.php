<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'options', 'correct_answer'];

    protected $casts = [
        'options' => 'array',
    ];
}
