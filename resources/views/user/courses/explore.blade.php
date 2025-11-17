@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-[#0A0E19] text-gray-100 relative overflow-hidden">

    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#0A0E19] via-[#0E1426] to-[#141C33] opacity-90"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-8"
         id="explore-app"
         data-course-id="{{ $courseId }}">

        <!-- LEFT: COURSE DETAILS -->
        <div class="flex-1">

            <!-- Title + Bio -->
            <div class="mb-6">
                <h1 id="course-title"
                    class="text-4xl font-bold text-white mb-3 tracking-wide drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                    Loading...
                </h1>
                <p id="course-bio" class="text-[#A1A9C4] text-lg leading-relaxed">
                    Please wait while we load the course details.
                </p>
            </div>

            <!-- Meta -->
            <div class="flex items-center flex-wrap gap-6 text-sm text-[#A1A9C4] border-t border-b border-white/10 py-3">
                <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> <span id="trainer-name">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Experience:</span> <span id="trainer-exp">—</span></p>
                <p><span class="font-semibold text-[#00C2FF]">Created:</span> <span id="created-date">—</span></p>
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

            <!-- ⭐ FEEDBACK SECTION -->
            <div class="mt-10">
                <h2 class="text-2xl font-semibold text-white mb-6">Course Feedback</h2>

                <!-- Rating Summary -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-6">
                    <div class="flex flex-col lg:flex-row justify-between gap-8">

                        <!-- Left -->
                        <div>
                            <p class="text-5xl font-bold text-white">
                                4.9 <span class="text-2xl text-yellow-400 align-top">/ 5</span>
                            </p>

                            <div class="flex text-yellow-400 text-2xl mt-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>

                            <p class="text-gray-400 text-sm mt-2">Based on 18 reviews</p>
                        </div>

                        <!-- Middle -->
                        <div class="flex-1">
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-300">5 Stars</span>
                                    <div class="bg-gray-700 h-2 flex-1 rounded">
                                        <div class="bg-[#00C2FF] h-2 rounded" style="width: 85%"></div>
                                    </div>
                                    <span class="text-gray-400">15</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-gray-300">4 Stars</span>
                                    <div class="bg-gray-700 h-2 flex-1 rounded">
                                        <div class="bg-[#00C2FF] h-2 rounded" style="width: 10%"></div>
                                    </div>
                                    <span class="text-gray-400">2</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-gray-300">3 Stars</span>
                                    <div class="bg-gray-700 h-2 flex-1 rounded">
                                        <div class="bg-[#00C2FF] h-2 rounded" style="width: 5%"></div>
                                    </div>
                                    <span class="text-gray-400">1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right -->
                        @if($isPurchased)
                        <div class="flex items-center">
                            <button id="open-feedback-modal"
                                class="bg-[#00C2FF] hover:bg-[#0098CC] text-white px-5 py-2 rounded-lg shadow">
                                Leave Us Feedback
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- FEEDBACK LIST -->
                <div id="feedback-list" class="space-y-4"></div>
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
                        <p id="trainer-exp-2" class="text-[#A1A9C4] text-sm">Experience: —</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: PURCHASE CARD -->
        <div class="w-full lg:w-80">
            <div class="sticky top-20 bg-[#101B2E]/80 backdrop-blur-lg border border-[#1E2B4A] rounded-2xl p-6">

                <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                     class="rounded-xl mb-4 w-full h-40 object-cover border border-[#1E2B4A]">

                <p class="text-2xl font-semibold text-white mb-2">
                    ₹<span id="course-price">—</span>
                </p>
                <p class="text-[#A1A9C4] text-sm mb-4">Full lifetime access · Certificate of completion</p>

                <div class="flex gap-3">
                    @if(!$isPurchased)
                        <a href="{{ route('payment.stripe', ['courseId' => $courseId]) }}"
                           class="flex-1 text-center bg-[#00C2FF] text-white py-3 rounded-lg">
                            Buy Now
                        </a>
                    @else
                        <a href="{{ route('user.courses.view', ['courseId' => $courseId]) }}"
                           class="flex-1 text-center bg-green-600 text-white py-3 rounded-lg">
                            Open Course
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($isPurchased)
<!-- ⭐ FEEDBACK MODAL -->
<div id="feedback-modal"
     class="fixed inset-0 bg-black/70 backdrop-blur-md hidden flex items-center justify-center z-50">

    <div class="bg-[#0F1627] border border-white/10 rounded-2xl p-6 w-full max-w-md shadow-lg relative">

        <button id="close-feedback-modal"
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">&times;</button>

        <h2 class="text-xl font-semibold text-white mb-4">Leave Your Feedback</h2>

        <!-- Rating Stars -->
        <div id="modal-rating-stars" class="flex gap-2 text-3xl cursor-pointer mb-4 text-gray-500">
            <i data-star="1" class="fa-solid fa-star"></i>
            <i data-star="2" class="fa-solid fa-star"></i>
            <i data-star="3" class="fa-solid fa-star"></i>
            <i data-star="4" class="fa-solid fa-star"></i>
            <i data-star="5" class="fa-solid fa-star"></i>
        </div>

        <textarea id="modal-feedback-comment"
                  rows="3"
                  class="w-full p-3 bg-[#101B2E] border border-white/10 rounded-lg text-gray-200"
                  placeholder="Write your comment (optional)..."></textarea>

        <button id="modal-submit-feedback"
                class="w-full mt-4 bg-[#00C2FF] text-white py-2 rounded-lg">
            Submit Feedback
        </button>
    </div>
</div>
@endif


<script>
document.addEventListener("DOMContentLoaded", () => {

    const app = document.getElementById("explore-app");
    const courseId = app.getAttribute("data-course-id");

    /* ---------------------------------------------------
       ⭐ JOIN SOCKET ROOM
    ------------------------------------------------------*/
    if (window.socket) {
        window.socket.emit("joinCourse", courseId);

        window.socket.on("feedback:new", (fb) => {
            appendFeedbackToUI(fb);
        });
    }


    /* ---------------------------------------------------
       LOAD COURSE DETAILS
    ------------------------------------------------------*/
    const titleEl = document.getElementById("course-title");
    const bioEl = document.getElementById("course-bio");
    const trainerNameEl = document.getElementById("trainer-name");
    const trainerExpEl = document.getElementById("trainer-exp");
    const trainerName2El = document.getElementById("trainer-name-2");
    const trainerExp2El = document.getElementById("trainer-exp-2");
    const dateEl = document.getElementById("created-date");
    const priceEl = document.getElementById("course-price");

    fetch(`/courses/${courseId}`)
        .then(res => res.json())
        .then(resp => {
            const c = resp.data;

            titleEl.textContent = c.title;
            bioEl.textContent = c.description;
            trainerNameEl.textContent = c.trainer?.name ?? "N/A";
            trainerName2El.textContent = c.trainer?.name ?? "N/A";
            trainerExpEl.textContent = (c.trainer?.experience_years ?? 0) + " Years";
            trainerExp2El.textContent = "Experience: " + (c.trainer?.experience_years ?? 0) + " Years";
            priceEl.textContent = c.price;
            dateEl.textContent = new Date(c.created_at).toLocaleDateString();
        });


    /* ---------------------------------------------------
       ⭐ LOAD FEEDBACK LIST FROM BACKEND
    ------------------------------------------------------*/
    const feedbackList = document.getElementById("feedback-list");

    function loadFeedback() {
        fetch(`/user/courses/${courseId}/feedback`)
            .then(res => res.json())
            .then(list => {
                feedbackList.innerHTML = "";
                list.forEach(fb => appendFeedbackToUI(fb));
            });
    }

    loadFeedback();


    /* ---------------------------------------------------
       ⭐ FUNCTION — APPEND FEEDBACK
    ------------------------------------------------------*/
    function appendFeedbackToUI(fb) {
        const div = document.createElement("div");
        div.className = "bg-white/5 border border-white/10 rounded-xl p-4";

        const userName = fb.user?.name ?? fb.user ?? "Unknown";
        const formattedDate = fb.created_at ? new Date(fb.created_at).toLocaleString() : "";

        div.innerHTML = `
            <div class="flex justify-between items-center">
                <h4 class="text-white font-medium">${userName}</h4>
                <p class="text-yellow-400">${"⭐".repeat(fb.rating)}</p>
            </div>
            <p class="text-gray-300 mt-1">${fb.comment ?? ''}</p>
            <p class="text-gray-500 text-xs mt-1">${formattedDate}</p>
        `;

        feedbackList.prepend(div);
    }


    /* ---------------------------------------------------
       ⭐ FEEDBACK MODAL
    ------------------------------------------------------*/
    const modal = document.getElementById("feedback-modal");
    const openBtn = document.getElementById("open-feedback-modal");
    const closeBtn = document.getElementById("close-feedback-modal");

    let modalRating = 0;

    if (openBtn) openBtn.onclick = () => modal.classList.remove("hidden");
    if (closeBtn) closeBtn.onclick = () => modal.classList.add("hidden");


    /* ---------------------------------------------------
       ⭐ RATING STARS — HOVER + CLICK ANIMATION
    ------------------------------------------------------*/
    const stars = document.querySelectorAll("#modal-rating-stars i");

    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            const val = star.getAttribute("data-star");
            stars.forEach(s => {
                s.style.color = (s.getAttribute("data-star") <= val) ? "#FFD700" : "#555";
            });
        });

        star.addEventListener("mouseout", () => {
            stars.forEach(s => {
                s.style.color = (s.getAttribute("data-star") <= modalRating) ? "#FFD700" : "#555";
            });
        });

        star.addEventListener("click", () => {
            modalRating = star.getAttribute("data-star");
            stars.forEach(s => {
                s.style.color = (s.getAttribute("data-star") <= modalRating) ? "#FFD700" : "#555";
            });
        });
    });


    /* ---------------------------------------------------
       ⭐ SUBMIT FEEDBACK
    ------------------------------------------------------*/
    document.getElementById("modal-submit-feedback").onclick = () => {

        if (modalRating == 0) {
            alert("Please select a rating.");
            return;
        }

        const comment = document.getElementById("modal-feedback-comment").value;

        fetch(`/user/courses/feedback/store`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                course_id: courseId,
                rating: modalRating,
                comment: comment
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.status) {
                alert("Error submitting feedback.");
                return;
            }

            modal.classList.add("hidden");
            document.getElementById("modal-feedback-comment").value = "";

            const newFb = {
                user: "{{ auth()->user()->name }}",
                rating: modalRating,
                comment: comment,
                created_at: new Date().toISOString()
            };

            appendFeedbackToUI(newFb);

            // Real-time socket push
            window.socket.emit("feedback:created", {
                courseId,
                ...newFb
            });
        });
    };

});
</script>

@endsection
