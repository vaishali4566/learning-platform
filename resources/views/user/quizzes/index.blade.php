@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Available Quizzes</h1>
    <ul class="space-y-3">
        @foreach($quizzes as $quiz)
            <li class="bg-gray-100 p-4 rounded flex justify-between items-center">
                <span>{{ $quiz->title }}</span>
                <a href="{{ route('user.quizzes.show', $quiz->id) }}" class="text-blue-700 font-semibold">Attempt Quiz</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
