@extends('layouts.user.index')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] py-10 px-6">
    <div class="max-w-3xl mx-auto">

        <!-- TIMER -->
        <div class="text-right mb-6">
            <div class="inline-block backdrop-blur-xl bg-white/5 border border-white/10 
                        px-6 py-3 rounded-2xl shadow-lg">
                <span class="text-gray-300 text-lg font-medium">Time Left:</span>
                <span id="timer" class="text-[#FF5F5F] text-2xl font-bold ml-2">
                    <span id="minutes">00</span>:<span id="seconds">00</span>
                </span>
            </div>
        </div>

        <!-- QUESTION CARD -->
        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 
                    rounded-3xl shadow-2xl p-10 transition hover:border-white/20">

            <!-- INFO BUTTON -->
            <button id="sourceBtn" type="button"
                class="absolute top-6 right-6 text-[#00C2FF] hover:text-white transition text-2xl"
                title="View Source">
                ℹ️
            </button>

            <!-- SOURCE POPUP -->
            <div id="sourcePopup"
                class="hidden absolute top-14 right-6 bg-[#0A1221] border border-[#00C2FF]/40
                       text-gray-300 p-4 rounded-xl shadow-lg w-72 z-50 text-sm">
                <strong class="text-[#00C2FF]">Source:</strong><br>
                {{ $question->source ?? 'No source available.' }}
            </div>

            <h3 class="text-2xl font-semibold mb-6 text-[#00C2FF]">
                Q{{ $qIndex + 1 }}. {{ $question->question_text }}
            </h3>

            <form method="POST" action="{{ route('user.practice.submit', $attempt->id) }}">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="hidden" name="q" value="{{ $qIndex }}">

                <!-- OPTIONS -->
                <div class="space-y-4 mt-6">
                    @foreach(['a','b','c','d'] as $opt)
                        <label class="flex items-start gap-4 cursor-pointer 
                                      p-4 rounded-2xl bg-white/5 border border-white/10 
                                      hover:bg-white/10 hover:border-[#00C2FF]/40
                                      transition">
                            <input 
                                type="radio" 
                                name="selected_option" 
                                value="{{ $opt }}"
                                {{ ($saved?->selected_option == $opt) ? 'checked' : '' }}
                                class="w-5 h-5 mt-1 accent-[#00C2FF]"
                            >
                            <span class="text-gray-200 text-lg leading-relaxed">
                                <strong class="text-[#00C2FF]">{{ strtoupper($opt) }}.</strong>
                                {{ $question['option_'.$opt] }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <!-- NAVIGATION BUTTONS -->
                <div class="flex justify-between mt-10">

                    @if($qIndex > 0)
                        <button 
                            type="submit" 
                            name="action" 
                            value="prev"
                            class="px-6 py-3 rounded-xl bg-white/10 border border-white/10 
                                   text-gray-300 hover:bg-white/20 transition">
                            ← Previous
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($qIndex < $total - 1)
                        <button 
                            type="submit" 
                            name="action" 
                            value="next"
                            class="px-6 py-3 rounded-xl bg-[#00C2FF] text-[#0A0F1B] 
                                   hover:bg-[#00AEE3] font-semibold shadow-lg transition">
                            Save & Next →
                        </button>
                    @else
                        <button 
                            type="submit" 
                            name="action" 
                            value="finish"
                            class="px-8 py-4 rounded-xl bg-green-500 text-white 
                                   font-bold hover:bg-green-600 shadow-lg transition text-lg">
                            Submit Test
                        </button>
                    @endif
                </div>

            </form>
        </div>

    </div>
</div>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const endAtTimestamp = {{ $endAtTimestamp }};
    const minutesSpan = document.getElementById('minutes');
    const secondsSpan = document.getElementById('seconds');
    let alreadySubmitted = false;

    function updateTimer() {
        const now = Date.now();
        let diff = Math.floor((endAtTimestamp - now) / 1000);

        if (diff <= 0) {
            if (!alreadySubmitted) {
                alreadySubmitted = true;
                document.getElementById('timer').innerHTML =
                    "<span class='text-red-500 font-bold text-3xl'>Time's Up!</span>";
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

    updateTimer();
    setInterval(updateTimer, 1000);

    // SOURCE POPUP LOGIC
    const btn = document.getElementById('sourceBtn');
    const popup = document.getElementById('sourcePopup');

    btn.addEventListener('click', () => {
        popup.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !popup.contains(e.target)) {
            popup.classList.add('hidden');
        }
    });
});
</script>

@endsection
