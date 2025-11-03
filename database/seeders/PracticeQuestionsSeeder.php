<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PracticeQuestion;

class PracticeQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        PracticeQuestion::create([
            'question' => 'What is 2 + 2?',
            'options' => ['1', '2', '3', '4'],
            'correct_answer' => '4',
        ]);

        PracticeQuestion::create([
            'question' => 'Capital of India?',
            'options' => ['Mumbai', 'Delhi', 'Kolkata', 'Goa'],
            'correct_answer' => 'Delhi',
        ]);
    }
}
