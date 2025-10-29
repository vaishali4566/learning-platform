@extends('layouts.user.index')

@section('content')
<div
    class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] flex justify-center items-start pt-16 px-4"
    id="explore-app"
    data-course-id="{{ $courseId }}">

    <div class="w-full max-w-3xl bg-[#0F172A]/60 backdrop-blur-xl border border-[#1E293B]/60 rounded-2xl shadow-2xl p-8 text-gray-200 space-y-6">

        <div class="border-b border-gray-700 pb-4">
            <h1 class="text-2xl font-semibold text-[#60A5FA]" id="course-title">Loading...</h1>
            <p class="text-sm text-gray-400 mt-2" id="course-bio">Please wait while we load course details.</p>
        </div>

        <div class="space-y-2 text-sm">
            <p><span class="font-semibold text-gray-300">Trainer:</span> <span id="trainer-name" class="text-gray-400"></span></p>
            <p><span class="font-semibold text-gray-300">Experience:</span> <span id="trainer-exp" class="text-gray-400"></span></p>
            <p><span class="font-semibold text-gray-300">Created At:</span> <span id="created-date" class="text-gray-400"></span></p>
        </div>

        <div class="pt-4">
            <button
                id="buy-now-btn"
                class="w-full py-2.5 rounded-lg font-semibold text-white text-sm tracking-wide
                       bg-gradient-to-r from-[#2F82DB] to-[#00C2FF]
                       shadow-[0_0_15px_rgba(0,194,255,0.25)]
                       hover:shadow-[0_0_25px_rgba(0,194,255,0.4)]
                       transition-all duration-300 ease-out hover:scale-[1.03]">
                Buy Now
            </button>

            <p id="buy-status" class="mt-3 text-xs font-medium text-green-400 hidden">Purchase successful!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const app = document.getElementById('explore-app');
        const courseId = app.getAttribute('data-course-id');

        const titleEl = document.getElementById('course-title');
        const bioEl = document.getElementById('course-bio');
        const trainerNameEl = document.getElementById('trainer-name');
        const trainerExpEl = document.getElementById('trainer-exp');
        const dateEl = document.getElementById('created-date');
        const buyBtn = document.getElementById('buy-now-btn');
        const buyStatus = document.getElementById('buy-status');

        // Fetch course data
        fetch(`/courses/${courseId}`)
            .then(res => res.json())
            .then(data => {
                titleEl.textContent = data.data.title || 'Untitled Course';
                bioEl.textContent = data.data.description || 'No description available.';
                trainerNameEl.textContent = data.data.trainer.name || 'Unknown';
                trainerExpEl.textContent = data.data.trainer.experience_years + " Years" || 'N/A';
                dateEl.textContent = new Date(data.data.created_at).toLocaleDateString() || '';
            })
            .catch(error => {
                titleEl.textContent = 'Failed to load course.';
                bioEl.textContent = 'An error occurred.';
                console.error(error);
            });

        // Handle Buy Now
        buyBtn.addEventListener('click', function() {
            buyBtn.disabled = true;
            buyBtn.textContent = 'Processing...';
            buyStatus.classList.add('hidden');

            fetch(`/api/courses/${courseId}/purchase`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ course_id: courseId })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Purchase failed.');
                    return res.json();
                })
                .then(data => {
                    buyBtn.textContent = 'Purchased';
                    buyStatus.textContent = 'Purchase successful!';
                    buyStatus.classList.remove('hidden');
                })
                .catch(error => {
                    buyBtn.textContent = 'Buy Now';
                    alert('Purchase failed. Please try again.');
                    console.error(error);
                })
                .finally(() => {
                    buyBtn.disabled = false;
                });
        });
    });
</script>
@endsection
