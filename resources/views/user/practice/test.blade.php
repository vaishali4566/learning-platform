@extends('layouts.user.index')

@section('content')

<div class="max-w-3xl mx-auto mt-6">

    <!-- TIMER -->
    <div class="text-right mb-4 text-xl font-semibold text-red-600">
        Time Left: <span id="timer">{{ $time_left }}</span>
    </div>

    <!-- QUESTION -->
    <div class="bg-white shadow p-6 rounded">
        <h3 class="text-lg font-bold mb-4">
            Q{{ $currentIndex + 1 }}. {{ $question->question }}
        </h3>

        <form method="POST" action="{{ route('user.practice.saveAnswer') }}">
            @csrf
            
            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="index" value="{{ $currentIndex }}">

            <!-- OPTIONS RADIO -->
            @foreach(['A','B','C','D'] as $opt)
                <label class="block mb-2">
                    <input type="radio" name="selected_option" value="{{ $opt }}"
                        {{ ($savedAnswer == $opt) ? 'checked' : '' }}>
                    {{ $opt }}. {{ $question['option_'.$opt] }}
                </label>
            @endforeach

            <!-- NAVIGATION -->
            <div class="flex justify-between mt-6">
                @if($currentIndex > 0)
                <a href="{{ route('user.practice.test', ['attempt' => $attempt->id, 'index' => $currentIndex - 1]) }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded">Previous</a>
                @endif

                @if($currentIndex < $totalQuestions - 1)
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Save & Next</button>
                @else
                <button class="px-4 py-2 bg-green-600 text-white rounded">Submit Test</button>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TIMER SCRIPT -->
<script>
let totalSeconds = {{ $time_left }};
let timerElement = document.getElementById("timer");

let interval = setInterval(() => {
    let min = Math.floor(totalSeconds / 60);
    let sec = totalSeconds % 60;

    timerElement.textContent = `${min}:${sec < 10 ? '0'+sec : sec}`;

    if (totalSeconds <= 0) {
        clearInterval(interval);
        alert("Time is over! Submitting test...");
        window.location.href = "{{ route('user.practice.submit', $attempt->id) }}";
    }

    totalSeconds--;
}, 1000);
</script>

@endsection
