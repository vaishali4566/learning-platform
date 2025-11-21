@extends('layouts.user.index')

@section('content')

<div class="max-w-3xl mx-auto mt-6">

    <!-- TIMER -->
    <div class="text-right mb-4 text-2xl font-bold text-red-600">
        Time Left: 
        <span id="timer">
            <span id="minutes">00</span>:<span id="seconds">00</span>
        </span>
    </div>

    <!-- QUESTION CARD -->
    <div class=" shadow-lg rounded-lg p-8">
        <h3 class="text-xl font-bold mb-6 text-white">
            Q{{ $qIndex + 1 }}. {{ $question->question }}
        </h3>

        <form method="POST" action="{{ route('user.practice.submit', $attempt->id) }}">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="q" value="{{ $qIndex }}">

            <!-- OPTIONS -->
            @foreach(['a','b','c','d'] as $opt)
                <label class="flex items-center mb-4 cursor-pointer text-lg">
                    <input type="radio" name="selected_option" value="{{ $opt }}"
                        {{ ($saved?->selected_option == $opt) ? 'checked' : '' }}
                        class="mr-4 w-5 h-5 text-blue-600">
                    <span>{{ strtoupper($opt) }}. {{ $question['option_'.$opt] }}</span>
                </label>
            @endforeach

            <!-- NAVIGATION BUTTONS -->
            <div class="flex justify-between mt-10">
                @if($qIndex > 0)
                    <button type="submit" name="action" value="prev"
                        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                        ← Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($qIndex < $total - 1)
                    <button type="submit" name="action" value="next"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Save & Next →
                    </button>
                @else
                    <button type="submit" name="action" value="finish"
                        class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition text-lg">
                        Submit Test
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- SMOOTH TIMER SCRIPT (No flash, no error) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const endAtTimestamp = {{ $endAtTimestamp }}; // Server exact end time in ms
    const minutesSpan    = document.getElementById('minutes');
    const secondsSpan    = document.getElementById('seconds');
    let alreadySubmitted = false;

    function updateTimer() {
        const now = Date.now();
        let diff  = Math.floor((endAtTimestamp - now) / 1000);

        if (diff <= 0) {
            if (!alreadySubmitted) {
                alreadySubmitted = true;
                document.getElementById('timer').innerHTML = 
                    "<span class='text-red-700 font-bold text-3xl'>Time's Up!</span>";
                alert("Time is over! Submitting your test...");
                window.location.href = "{{ route('user.practice.result', $attempt->id) }}?timeout=1";
            }
            return;
        }

        const mins = String(Math.floor(diff / 60)).padStart(2, '0');
        const secs = String(diff % 60).padStart(2, '0');

        minutesSpan.textContent = mins;
        secondsSpan.textContent = secs;
    }

    // Instant first update → no wrong number flash
    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>

@endsection