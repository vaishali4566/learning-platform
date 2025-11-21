@extends('layouts.user.index')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6  shadow rounded">

    <h2 class="text-2xl font-bold mb-4">Your Result</h2>

    <p class="text-lg">
        Score: <strong>{{ $attempt->score }}%</strong>
    </p>

    <p class="text-lg">
        Correct Answers: <strong>{{ $attempt->correct_answers }}</strong> /
        {{ $attempt->total_questions }}
    </p>

    <p class="mt-2">
        Time Taken: <strong>{{ gmdate('i:s', $attempt->time_taken) }}</strong>
    </p>

    <h3 class="text-xl font-semibold mt-6 mb-3">Your Answers</h3>

    @foreach($attempt->answers as $ans)
        <div class="p-3 mb-2 border rounded 
            {{ $ans->is_correct ? 'bg-green' : 'bg-red' }}">

            <p><strong>Q:</strong> {{ $ans->question->question }}</p>
            <p><strong>Your Answer:</strong> {{ strtoupper($ans->selected_option) }}</p>
            <p><strong>Correct:</strong> {{ strtoupper($ans->question->correct_option) }}</p>
        </div>
    @endforeach

</div>
@endsection
