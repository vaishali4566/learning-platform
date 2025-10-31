@extends('layouts.user.index')

@section('content')
<div
    class="relative min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] flex justify-center items-start pt-16 px-4"
    id="explore-app"
    data-course-id="{{ $courseId }}">
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/10 blur-3xl opacity-30 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-3xl bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl shadow-[0_0_25px_rgba(0,194,255,0.08)] p-8 transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,194,255,0.12)]">
        <!-- Title -->
        <h1 class="text-4xl font-semibold text-[#E6EDF7] mb-4 tracking-wide drop-shadow-[0_0_4px_rgba(0,194,255,0.2)]" id="course-title">
            Loading...
        </h1>

        <p class="text-[#A1A9C4] leading-relaxed mb-6" id="course-bio">
            Please wait while we load the course details.
        </p>

        <div class="border-t border-white/10 pt-5 space-y-3">
            <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> <span id="trainer-name" class="text-[#E6EDF7]">—</span></p>
            <p><span class="font-semibold text-[#00C2FF]">Experience:</span> <span id="trainer-exp" class="text-[#E6EDF7]">—</span></p>
            <p><span class="font-semibold text-[#00C2FF]">Created At:</span> <span id="created-date" class="text-[#E6EDF7]">—</span></p>
        </div>

        <!-- Buy Section -->
        <div class="pt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <button
                id="buy-now-btn"
                class="relative overflow-hidden bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white font-medium px-6 py-2 rounded-lg shadow-[0_0_10px_rgba(0,194,255,0.15)] hover:shadow-[0_0_15px_rgba(0,194,255,0.25)] hover:scale-[1.02] transition-all duration-200">
                Buy Now
            </button>

            <p id="buy-status" class="text-sm font-medium text-green-400 hidden">
                Purchase successful!
            </p>
        </div>
    </div>
</div>

<style>
    /* Animated gradient border shimmer */
    .bg-border {
        background: linear-gradient(130deg, rgba(0, 194, 255, 0.4), rgba(47, 130, 219, 0.4), transparent);
        background-size: 300% 300%;
        animation: shimmer 6s infinite alternate;
    }

    @keyframes shimmer {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }

    /* Subtle shimmer for loading placeholders */
    @keyframes pulse {

        0%,
        100% {
            opacity: 0.4;
        }

        50% {
            opacity: 1;
        }
    }

    .loading-placeholder {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 4px;
        height: 1.2rem;
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>

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

        // Fetch course data dynamically
        fetch(`/courses/${courseId}`)
            .then(res => res.json())
            .then(data => {
                const course = data.data;
                titleEl.textContent = course.title || 'Untitled Course';
                bioEl.textContent = course.description || 'No description available.';
                trainerNameEl.textContent = course.trainer?.name || 'Unknown';
                trainerExpEl.textContent = (course.trainer?.experience_years || 'N/A') + ' Years';
                dateEl.textContent = new Date(course.created_at).toLocaleDateString();
            })
            .catch(error => {
                titleEl.textContent = 'Failed to load course.';
                bioEl.textContent = 'An error occurred while loading details.';
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
                    body: JSON.stringify({
                        course_id: courseId
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Purchase failed.');
                    return res.json();
                })
                .then(() => {
                    buyBtn.textContent = 'Purchased ✔';
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