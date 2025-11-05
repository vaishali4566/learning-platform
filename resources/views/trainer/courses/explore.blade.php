@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0A0E19] text-gray-100 relative overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#0A0E19] via-[#0E1426] to-[#141C33] opacity-90"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-8" id="explore-app" data-course-id="{{ $courseId }}">

        <!-- Left: Course Details -->
        <div class="flex-1">
            <!-- Course Header -->
            <div class="mb-6">
                <h1 id="course-title" class="text-4xl font-bold text-white mb-3 tracking-wide drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                    Loading...
                </h1>
                <p id="course-bio" class="text-[#A1A9C4] text-lg leading-relaxed">
                    Please wait while we load the course details.
                </p>
            </div>

            <!-- Course Meta -->
            <div class="flex items-center flex-wrap gap-6 text-sm text-[#A1A9C4] border-t border-b border-white/10 py-3">
                <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> <span id="trainer-name" class="text-gray-200">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Experience:</span> <span id="trainer-exp" class="text-gray-200">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Created:</span> <span id="created-date" class="text-gray-200">—</span></p>
            </div>

            <!-- What You’ll Learn -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-white mb-3">What you'll learn</h2>
                <ul class="space-y-2 text-[#A1A9C4] list-disc list-inside">
                    <li>Understand key concepts of this course in depth</li>
                    <li>Practical projects and real-world use cases</li>
                    <li>Hands-on lessons with step-by-step explanation</li>
                    <li>Lifetime access and certificate on completion</li>
                </ul>
            </div>

            <!-- About Trainer -->
            <div class="mt-10">
                <h2 class="text-2xl font-semibold text-white mb-3">About the Trainer</h2>
                <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl p-5">
                    <div class="w-16 h-16 bg-[#1C2541] rounded-full flex items-center justify-center text-2xl font-semibold text-[#00C2FF] shadow-lg">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h3 id="trainer-name-2" class="text-lg font-medium text-white">—</h3>
                        <p class="text-[#A1A9C4] text-sm" id="trainer-exp-2">Experience: —</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Purchase Card -->
        <div class="w-full lg:w-80">
            <div class="sticky top-20 bg-[#101B2E]/80 backdrop-blur-lg border border-[#1E2B4A] rounded-2xl p-6 shadow-[0_0_25px_rgba(0,194,255,0.1)] hover:shadow-[0_0_30px_rgba(0,194,255,0.15)] transition-all duration-300">
                <img id="course-image" src="https://via.placeholder.com/400x200"
                     alt="Course Image" class="rounded-xl mb-4 w-full h-40 object-cover border border-[#1E2B4A]">

                <p class="text-2xl font-semibold text-white mb-2">₹<span id="course-price">—</span></p>
                <p class="text-[#A1A9C4] text-sm mb-4">Full lifetime access · Certificate of completion</p>

                <button id="buy-now-btn"
                    class="w-full bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium py-3 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                    Buy Now
                </button>

                <p id="buy-status" class="text-sm font-medium text-green-400 hidden text-center mt-3">
                    Purchase successful!
                </p>

                <div class="border-t border-[#1E2B4A] mt-5 pt-3 text-sm text-[#A1A9C4]">
                    <p><i class="fa-solid fa-play-circle text-[#00C2FF]"></i> 12 Hours on-demand video</p>
                    <p><i class="fa-solid fa-infinity text-[#00C2FF]"></i> Lifetime access</p>
                    <p><i class="fa-solid fa-certificate text-[#00C2FF]"></i> Certificate included</p>
                </div>
            </div>
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
    const trainerName2El = document.getElementById('trainer-name-2');
    const trainerExp2El = document.getElementById('trainer-exp-2');
    const dateEl = document.getElementById('created-date');
    const courseImage = document.getElementById('course-image');
    const coursePrice = document.getElementById('course-price');
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
            trainerName2El.textContent = course.trainer?.name || 'Unknown';
            trainerExp2El.textContent = `Experience: ${(course.trainer?.experience_years || 'N/A')} Years`;
            dateEl.textContent = new Date(course.created_at).toLocaleDateString();
            coursePrice.textContent = course.price || '—';
            if (course.image) courseImage.src = `/storage/${course.image}`;
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
                body: JSON.stringify({ course_id: courseId })
            })
            .then(res => {
                if (!res.ok) throw new Error('Purchase failed.');
                return res.json();
            })
            .then(() => {
                buyBtn.textContent = 'Purchased ✔';
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
