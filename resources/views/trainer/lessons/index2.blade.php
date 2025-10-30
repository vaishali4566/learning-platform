@extends('layouts.trainer.lesson')

@section('content')
<div id="lesson-app"
    data-course-id="{{ $courseId }}"
    class="min-h-screen flex bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] pt-[64px] transition-all duration-300">

    <!-- Sidebar -->
    <aside id="lesson-sidebar"
        class="w-72 bg-white/10 backdrop-blur-2xl border-r border-white/10 shadow-[inset_0_0_25px_rgba(0,194,255,0.05)] p-5 transition-all duration-500 ease-in-out overflow-y-auto max-h-screen">
        <h2 class="text-xl font-semibold text-[#00C2FF] mb-5 flex items-center gap-2">
            📘 Lessons
        </h2>
        <ul id="lesson-list"
            class="space-y-2 overflow-y-auto max-h-[calc(100vh-8rem)] scrollbar-thin scrollbar-thumb-[#00C2FF]/20 scrollbar-track-transparent">
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto transition-all duration-300 ease-in-out">
        <div
            class="relative bg-white/10 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-[0_0_25px_rgba(0,194,255,0.08)] p-8 transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,194,255,0.12)]">
            <!-- Title -->
            <h1 id="lesson-title"
                class="text-3xl font-semibold mb-4 tracking-wide text-[#E6EDF7] drop-shadow-[0_0_4px_rgba(0,194,255,0.15)] transition-all duration-200">
                Loading...
            </h1>

            <!-- Lesson Content -->
            <div id="lesson-content" class="text-[#A1A9C4] leading-relaxed space-y-4">
                <div class="loading-placeholder w-3/4 h-4 rounded"></div>
                <div class="loading-placeholder w-full h-4 rounded"></div>
                <div class="loading-placeholder w-2/3 h-4 rounded"></div>
            </div>
        </div>
    </main>
</div>

<style>
    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: rgba(0, 194, 255, 0.3);
        border-radius: 6px;
    }

    /* Sidebar Hide Animation */
    .sidebar-collapsed {
        width: 0 !important;
        padding: 0 !important;
        opacity: 0;
        overflow: hidden;
    }

    /* Lesson buttons */
    #lesson-list button {
        width: 100%;
        text-align: left;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #A1A9C4;
        background: transparent;
        transition: all 0.25s ease;
    }

    #lesson-list button:hover {
        background: rgba(0, 194, 255, 0.08);
        color: #E6EDF7;
    }

    .lesson-active {
        background: rgba(0, 194, 255, 0.15);
        border-left: 3px solid #00C2FF;
        color: #E6EDF7;
        font-weight: 600;
        box-shadow: inset 0 0 10px rgba(0, 194, 255, 0.1);
    }

    /* Loading shimmer */
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
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Video styling */
    video {
        border-radius: 16px;
        outline: none;
        box-shadow: 0 0 20px rgba(0, 194, 255, 0.15);
    }

    /* Smooth transitions for content */
    #lesson-content {
        transition: opacity 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const app = document.getElementById('lesson-app');
        const courseId = app.getAttribute('data-course-id');
        const sidebar = document.getElementById('lesson-sidebar');
        const lessonList = document.getElementById('lesson-list');
        const lessonTitle = document.getElementById('lesson-title');
        const lessonContent = document.getElementById('lesson-content');
        let activeLessonId = null;

        // Sidebar Toggle Logic (connected to navbar)
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('sidebar-collapsed');
            });
        }

        // Fetch lessons
        fetch(`/courses/${courseId}/lessons`)
            .then(res => res.json())
            .then(lessons => {
                if (!lessons.length) {
                    lessonTitle.textContent = "No lessons available.";
                    return;
                }

                lessons.forEach((lesson, index) => {
                    const li = document.createElement('li');
                    const btn = document.createElement('button');
                    btn.textContent = lesson.title;
                    btn.setAttribute('data-lesson-id', lesson.id);

                    btn.addEventListener('click', () => {
                        loadLesson(lesson.id, lesson.title);
                        document.querySelectorAll('#lesson-list button').forEach(el => el.classList.remove('lesson-active'));
                        btn.classList.add('lesson-active');
                    });

                    li.appendChild(btn);
                    lessonList.appendChild(li);

                    // Auto-load first lesson
                    if (index === 0) btn.click();
                });
            })
            .catch(err => {
                lessonTitle.textContent = "Failed to load lessons.";
                console.error(err);
            });

        // Load lesson
        function loadLesson(lessonId, title) {
            if (lessonId === activeLessonId) return;
            activeLessonId = lessonId;

            lessonTitle.textContent = title;
            lessonContent.style.opacity = "0.4";
            lessonContent.innerHTML = `
                <div class="loading-placeholder w-3/4 h-4 rounded"></div>
                <div class="loading-placeholder w-full h-4 rounded"></div>
                <div class="loading-placeholder w-2/3 h-4 rounded"></div>
            `;

            fetch(`/lessons/${lessonId}/stream`)
                .then(response => {
                    const contentType = response.headers.get('Content-Type');
                    if (contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        lessonContent.innerHTML = `
                            <div class="flex justify-center">
                                <video class="w-full max-w-3xl rounded-lg mt-4" controls autoplay>
                                    <source src="/lessons/${lessonId}/stream" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        `;
                    }
                })
                .then(data => {
                    if (!data) return;
                    if (data.content_type === 'text') {
                        lessonContent.innerHTML = `
                            <div class="text-[#A1A9C4] leading-relaxed">
                                ${data.text_content}
                            </div>
                        `;
                    }
                    lessonContent.style.opacity = "1";
                })
                .catch(err => {
                    lessonContent.innerHTML = `<p class="text-red-400">Error loading lesson content.</p>`;
                    lessonContent.style.opacity = "1";
                });
        }
    });
</script>
@endsection