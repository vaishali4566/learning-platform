<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeQuestion extends Model
{
    protected $fillable = [
        'practice_test_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation'
    ];

    // Relation
    public function test()
    {
        return $this->belongsTo(PracticeTest::class, 'practice_test_id');
    }
}
