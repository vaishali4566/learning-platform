<h1>Edit Quiz: {{ $quiz->title }}</h1>

{{-- Success or error messages --}}
@if(session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Display total_marks and passing_marks --}}
<p><strong>Total Marks:</strong> {{ $quiz->total_marks }}</p>
<p><strong>Passing Marks (33%):</strong> {{ $quiz->passing_marks }}</p>

<h2>Add Question</h2>
<form action="{{ route('trainer.quizzes.questions.store', $quiz->id) }}" method="POST">
    @csrf
    <textarea name="question_text" placeholder="Question Text" required></textarea><br>
    <input type="number" name="marks" placeholder="Marks for this question" required><br>
    <input type="text" name="options[]" placeholder="Option 1" required><br>
    <input type="text" name="options[]" placeholder="Option 2" required><br>
    <input type="text" name="options[]" placeholder="Option 3" required><br>
    <input type="text" name="options[]" placeholder="Option 4" required><br>
    <input type="number" name="correct_option" placeholder="Correct Option Index (0-3)" required><br>
    <button type="submit">Add Question</button>
</form>

<h2>Existing Questions</h2>

@if($quiz->questions && $quiz->questions->count() > 0)
    <ul>
        @foreach($quiz->questions as $q)
            <li>
                {{ $q->question_text }} (Marks: {{ $q->marks }})
                @if(!empty($q->options))
                    <ul>
                        @foreach((array)$q->options as $index => $option)
                            <li>{{ $index }}: {{ $option }}
                                @if($index == $q->correct_option)
                                    <strong>(Correct)</strong>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p>No questions added yet.</p>
@endif

{{-- Finalize Quiz Button --}}
<form action="{{ route('trainer.quizzes.finalize', $quiz->id) }}" method="POST" style="margin-top:20px;">
    @csrf
    <button type="submit">Done / Finalize Quiz</button>
</form>
