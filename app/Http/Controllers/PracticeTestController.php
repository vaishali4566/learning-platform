<?php

namespace App\Http\Controllers;

use App\Models\PracticeTest;
use App\Models\PracticeQuestion;
use App\Models\Lesson;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PracticeTestController extends Controller
{
    // 1. List all practice tests
    public function index()
    {
        $tests = PracticeTest::with('lesson')->latest()->paginate(10);
        return view('trainer.practice-tests.index', compact('tests'));
    }

    // 2. Show create form
    public function create()
    {
        $lessons = Lesson::where('content_type', 'practice')->get();
        return view('trainer.practice-tests.create', compact('lessons'));
    }

    // 3. Store new practice test
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title'     => 'required|string|max:255',
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);
        if ($lesson->content_type !== 'practice') {
            return back()->with('error', 'Selected lesson must be of type Practice Test!');
        }

        if ($lesson->practice_test_id) {
            return back()->with('error', 'This lesson already has a Practice Test attached!');
        }
        $test = PracticeTest::create([
            'lesson_id' => $request->lesson_id,
            'title'     => $request->title,
            'total_questions' => 0,
        ]);

        Lesson::where('id', $request->lesson_id)
            ->update(['practice_test_id' => $test->id]);

        return redirect()->route('practice-tests.edit', $test->id)
            ->with('success', 'Practice Test Created Successfully! Now upload questions.');
    }

    // 4. Show test details (questions list)
    public function show($id)
    {
        $test = PracticeTest::findOrFail($id);
        $questions = PracticeQuestion::where('practice_test_id', $id)->get();

        return view('trainer.practice-tests.show', compact('test', 'questions'));
    }

    // 5. Show edit page (import questions)
    public function edit($id)
    {
        $test = PracticeTest::findOrFail($id);
        return view('trainer.practice-tests.edit', compact('test'));
    }

    // 6. Update test
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $test = PracticeTest::findOrFail($id);
        $test->update([
            'title' => $request->title
        ]);

        return back()->with('success', 'Practice Test updated!');
    }

    // 7. Delete test + all questions
    public function destroy($id)
    {
        $test = PracticeTest::findOrFail($id);
        $test->delete();

        return redirect()->route('practice-tests.index')
            ->with('success', 'Practice Test deleted successfully!');
    }

    // 8. Import Excel questions using PhpSpreadsheet
    public function importQuestions(Request $request, $testId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $filePath = $request->file('file')->getPathname();
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            return back()->with('error', 'Excel file is empty or invalid.');
        }

        // Use first row as header
        $header = array_map('strtolower', $rows[0]);

        for ($i = 1; $i < count($rows); $i++) {
    $currentRow = $rows[$i];

    // 1. Skip completely empty rows
    if (empty(array_filter($currentRow))) {
        continue;
    }

    $row = array_combine($header, $currentRow);

    // 2. Extra safety: agar question_text blank hai to skip
    if (empty(trim($row['question_text'] ?? ''))) {
        continue;
    }

    // 3. correct_option ke liye safe value
    $correctOption = trim($row['correct_option'] ?? '');
    $correctOption = strtolower($correctOption);
    if (!in_array($correctOption, ['a','b','c','d','e','f'])) {
        $correctOption = 'a'; // ya null ya error log
    }

    PracticeQuestion::create([
        'practice_test_id' => $testId,
        'question_text'    => trim($row['question_text'] ?? ''),
        'option_a'         => trim($row['option_a'] ?? ''),
        'option_b'         => trim($row['option_b'] ?? ''),
        'option_c'         => trim($row['option_c'] ?? ''),
        'option_d'         => trim($row['option_d'] ?? ''),
        'correct_option'   => $correctOption,
        'explanation'      => trim($row['explanation'] ?? ''),
    ]);
}

        // update total questions count
        $count = PracticeQuestion::where('practice_test_id', $testId)->count();
        PracticeTest::where('id', $testId)->update(['total_questions' => $count]);

        return back()->with('success', 'Questions Imported Successfully!');
    }
}
