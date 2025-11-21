<?php

namespace App\Imports;

use App\Models\PracticeQuestion;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PracticeQuestionsImport
{
    protected $testId;
    protected $filePath;

    public function __construct($testId, $filePath)
    {
        $this->testId = $testId;
        $this->filePath = $filePath;
    }

    public function import()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Assuming first row is heading
        $header = array_map('strtolower', $rows[0]);
        
        for ($i = 1; $i < count($rows); $i++) {
            $row = array_combine($header, $rows[$i]);

            PracticeQuestion::create([
                'practice_test_id' => $this->testId,
                'question_text' => $row['question'] ?? '',
                'option_a' => $row['option_a'] ?? '',
                'option_b' => $row['option_b'] ?? '',
                'option_c' => $row['option_c'] ?? '',
                'option_d' => $row['option_d'] ?? '',
                'correct_option' => strtolower($row['correct_option'] ?? ''),
                'explanation' => $row['explanation'] ?? null,
            ]);
        }
    }
}
