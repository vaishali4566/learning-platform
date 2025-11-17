@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0A0E19] text-gray-100 relative overflow-hidden">

    <!-- BG -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#0A0E19] via-[#0E1426] to-[#141C33] opacity-90"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-8"
         id="trainer-explore-app"
         data-course-id="{{ $courseId }}">

        <!-- LEFT -->
        <div class="flex-1">

            <!-- Title + Bio -->
            <div class="mb-6">
                <h1 id="course-title"
                    class="text-4xl font-bold text-white mb-3 tracking-wide drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                    Loading...
                </h1>
                <p id="course-bio" class="text-[#A1A9C4] text-lg leading-relaxed">
                    Please wait while we load course details.
                </p>
            </div>

            <!-- Meta -->
            <div class="flex items-center flex-wrap gap-6 text-sm text-[#A1A9C4] border-t border-b border-white/10 py-3">
                <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> <span id="trainer-name">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Experience:</span> <span id="trainer-exp">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Created:</span> <span id="created-date">—</span></p>
            </div>

            <!-- What You'll Learn -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-white mb-3">What you'll learn</h2>
                <ul class="space-y-2 text-[#A1A9C4] list-disc list-inside">
                    <li>Understand concepts in depth</li>
                    <li>Real-world use cases</li>
                    <li>Hands-on learning</li>
                </ul>
            </div>

            <!-- ⭐ FEEDBACK SECTION → always visible to trainer -->
            <div class="mt-10">
                <h2 class="text-2xl font-semibold text-white mb-6">Course Feedback</h2>

                <!-- Rating Summary -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-6">
                    <div class="flex flex-col lg:flex-row justify-between gap-8">

                        <!-- Left -->
                        <div>
                            <p id="avg-rating" class="text-5xl font-bold text-white">0.0 <span class="text-2xl text-yellow-400 align-top">/ 5</span></p>

                            <div id="avg-stars" class="flex text-yellow-400 text-2xl mt-1"></div>

                            <p id="total-reviews" class="text-gray-400 text-sm mt-2">Based on 0 reviews</p>
                        </div>

                        <!-- Middle -->
                        <div class="flex-1">
                            <div class="space-y-2 text-sm">

                                <template id="rating-row-template">
                                    <div class="flex items-center gap-3 rating-row">
                                        <span class="text-gray-300 star-label"></span>
                                        <div class="bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded progress-bar"></div>
                                        </div>
                                        <span class="text-gray-400 count"></span>
                                    </div>
                                </template>

                                <div id="rating-rows"></div>
                            </div>
                        </div>

                       
                    </div>
                </div>

                <!-- FEEDBACK LIST -->
                <div id="feedback-list" class="space-y-4"></div>
            </div>

            <!-- About Trainer -->
            <div class="mt-10">
                <h2 class="text-2xl font-semibold text-white mb-3">About the Trainer</h2>

                <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl p-5">
                    <div class="w-16 h-16 bg-[#1C2541] rounded-full flex items-center justify-center text-2xl font-semibold text-[#00C2FF]">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <h3 id="trainer-name-2" class="text-lg font-medium text-white">—</h3>
                        <p id="trainer-exp-2" class="text-[#A1A9C4] text-sm">Experience: —</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Purchase Card -->
<div class="w-full lg:w-80">
    <div class="sticky top-20 bg-[#101B2E]/80 backdrop-blur-lg border border-[#1E2B4A] rounded-2xl p-6">

        <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://via.placeholder.com/400x200' }}"
             class="rounded-xl mb-4 w-full h-40 object-cover border border-[#1E2B4A]">

        <p class="text-2xl font-semibold text-white mb-2">
            ₹{{ $course->price ?? '—' }}
        </p>

        <p class="text-[#A1A9C4] text-sm mb-4">Lifetime access · Certificate</p>

        <!-- ✅ Dynamic Buttons (Added from OLD Code) -->
        <div class="flex gap-3 mt-4">
            @if($isOwned || $isPurchased)
                <a href="{{route('trainer.courses.lessons.view', $courseId)}}"
                   class="flex-1 text-center bg-gradient-to-r from-[#16A34A] to-[#15803D] text-white font-medium py-3 rounded-lg shadow-md hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-300">
                    Open Course
                </a>
            @else
                <a href="{{route('payment.stripe.trainer', $courseId)}}"
                   class="flex-1 text-center bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium py-3 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                    Buy Now
                </a>
            @endif
        </div>

    </div>
</div>


    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {

    const app = document.getElementById("trainer-explore-app");
    const courseId = app.getAttribute("data-course-id");

    /* ---------------------------------------------------
       JOIN SOCKET ROOM (Trainer should receive new reviews live)
    ------------------------------------------------------*/
    if (window.socket) {
        window.socket.emit("joinCourse", courseId);

        window.socket.on("feedback:new", (fb) => {
            appendFeedbackToUI(fb);
            loadFeedbackSummary();
        });
    }


    /* ---------------------------------------------------
       LOAD COURSE DETAILS
    ------------------------------------------------------*/
    fetch(`/courses/${courseId}`)
        .then(res => res.json())
        .then(resp => {
            const c = resp.data;

            document.getElementById("course-title").textContent = c.title;
            document.getElementById("course-bio").textContent = c.description;

            document.getElementById("trainer-name").textContent = c.trainer?.name ?? "N/A";
            document.getElementById("trainer-name-2").textContent = c.trainer?.name ?? "N/A";

            document.getElementById("trainer-exp").textContent = (c.trainer?.experience_years ?? 0) + " Years";
            document.getElementById("trainer-exp-2").textContent = "Experience: " + (c.trainer?.experience_years ?? 0) + " Years";

            document.getElementById("created-date").textContent = new Date(c.created_at).toLocaleDateString();
        });


    /* ---------------------------------------------------
       LOAD FEEDBACK LIST
    ------------------------------------------------------*/
    const feedbackList = document.getElementById("feedback-list");

    function loadFeedback() {
        fetch(`/trainer/courses/${courseId}/feedback`)
            .then(res => res.json())
            .then(list => {
                feedbackList.innerHTML = "";
                list.forEach(fb => appendFeedbackToUI(fb));
            });
    }

    loadFeedback();


    function appendFeedbackToUI(fb) {
        const div = document.createElement("div");
        div.className = "bg-white/5 border border-white/10 rounded-xl p-4";

        const name = fb.user?.name ?? fb.user ?? "Unknown";
        const date = fb.created_at ? new Date(fb.created_at).toLocaleString() : "";

        div.innerHTML = `
            <div class="flex justify-between items-center">
                <h4 class="text-white font-medium">${name}</h4>
                <p class="text-yellow-400">${"⭐".repeat(fb.rating)}</p>
            </div>
            <p class="text-gray-300 mt-1">${fb.comment ?? ''}</p>
            <p class="text-gray-500 text-xs mt-1">${date}</p>
        `;

        feedbackList.prepend(div);
    }


    /* ---------------------------------------------------
       LOAD SUMMARY
    ------------------------------------------------------*/
    function loadFeedbackSummary() {
        fetch(`/trainer/courses/${courseId}/feedback/summary`)
            .then(res => res.json())
            .then(summary => {

                document.getElementById("avg-rating").innerHTML =
                    `${summary.average} <span class="text-2xl text-yellow-400 align-top">/ 5</span>`;

                // stars
                const stars = document.getElementById("avg-stars");
                stars.innerHTML = "";

                const full = Math.floor(summary.average);
                const half = summary.average % 1 !== 0;

                for (let i = 1; i <= 5; i++) {
                    if (i <= full) stars.innerHTML += `<i class="fa-solid fa-star"></i>`;
                    else if (i === full + 1 && half) stars.innerHTML += `<i class="fa-solid fa-star-half-stroke"></i>`;
                    else stars.innerHTML += `<i class="fa-regular fa-star"></i>`;
                }

                document.getElementById("total-reviews").textContent =
                    `Based on ${summary.total} reviews`;

                // rows
                const container = document.getElementById("rating-rows");
                const template = document.getElementById("rating-row-template");
                container.innerHTML = "";

                for (let star = 5; star >= 1; star--) {
                    const clone = template.content.cloneNode(true);

                    clone.querySelector(".star-label").textContent = `${star} Stars`;
                    clone.querySelector(".count").textContent = summary.counts[star];

                    const percent = summary.total ? (summary.counts[star] / summary.total) * 100 : 0;
                    clone.querySelector(".progress-bar").style.width = `${percent}%`;

                    container.appendChild(clone);
                }
            });
    }

    loadFeedbackSummary();

});
</script>
@endsection
