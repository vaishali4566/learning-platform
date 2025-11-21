@extends('layouts.user.index')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 shadow rounded">

    <h2 class="text-2xl text-white font-bold mb-4">{{ $test->title }}</h2>

    <p class="text-white mb-2">
        Total Questions: <strong>{{ $test->total_questions }}</strong>
    </p>

    <p class="text-white mb-4">
        Duration: <strong>10 minutes</strong>
    </p>

    <form method="POST" action="{{ route('user.practice.start.attempt', $test->lesson_id) }}">

        @csrf

        <button 
            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Start Test
        </button>
    </form>
</div>
@endsection
