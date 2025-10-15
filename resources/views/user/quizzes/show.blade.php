@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h1>
    <form action="{{ route('user.quizzes.submit', $quiz->id) }}" method="POST">
        @csrf
        @foreach($quiz->questions as $index => $q)
            <div class="mb-6 p-4 border rounded">
                <p class="font-semibold">Q{{ $index + 1 }}: {{ $q->question_text }}</p>
                @foreach($q->options as $optIndex => $option)
                    <label class="block mt-2">
                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $optIndex }}" required>
                        {{ $option }}
                    </label>
                @endforeach
            </div>
        @endforeach
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:opacity-90">Submit Quiz</button>
    </form>
</div>
@endsection
