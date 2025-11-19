@extends('layouts.user.lesson')

@section('content')
<div id="lesson-app"
    data-course-id="{{ $courseId }}"
    class="h-full flex bg-gradient-to-br from-[#05070F] via-[#0A0F1E] to-[#0C1328] text-[#E6EDF7] pt-[64px] transition-all duration-300">

    <!-- Sidebar -->
    <aside id="lesson-sidebar"
        class="w-72 bg-white/5 backdrop-blur-2xl border-r border-white/10 shadow-[inset_0_0_20px_rgba(0,194,255,0.05)] p-5 transition-all duration-500 ease-in-out overflow-y-auto max-h-screen group relative rounded-r-3xl">

        <!-- Course Header -->
        <div id="course-header"
            class="flex items-center gap-3 mb-6 transition-all duration-300 group-[.sidebar-collapsed]:justify-center">
            <div id="course-logo"
                class="relative flex items-center justify-center w-11 h-11 bg-[#0E1625]/80 border border-[#00C2FF]/20 rounded-2xl shadow-[0_0_8px_rgba(0,194,255,0.3)] text-[#00C2FF] cursor-pointer transition-transform hover:scale-110"
                data-tooltip="{{ $courseName ?? 'Course' }}">
                <i data-lucide="book-open" class="w-5 h-5"></i>
            </div>

            <h2 id="course-title"
                class="text-lg font-semibold text-[#E6EDF7] whitespace-nowrap tracking-wide transition-all duration-300 group-[.sidebar-collapsed]:opacity-0 group-[.sidebar-collapsed]:w-0 overflow-hidden">
                {{ $courseName ?? 'Course Title' }}
            </h2>
        </div>

        <!-- Lessons List -->
        <ul id="lesson-list"
            class="space-y-2 overflow-y-auto max-h-[calc(100vh-10rem)] scrollbar-thin scrollbar-thumb-[#00C2FF]/40 scrollbar-track-transparent">
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto transition-all duration-300">
        <div
            class="relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-[0_0_25px_rgba(0,194,255,0.05)] p-10 transition hover:shadow-[0_0_35px_rgba(0,194,255,0.08)] hover:scale-[1.005] duration-300">

            <h1 id="lesson-title"
                class="text-2xl font-semibold mb-8 tracking-wide text-[#E6EDF7] drop-shadow-[0_0_3px_rgba(0,194,255,0.1)] transition-all">
                Loading...
            </h1>

            <div id="lesson-content" class="text-[#A1A9C4] leading-relaxed space-y-5">
                <div class="loading-placeholder w-3/4 h-4 rounded"></div>
                <div class="loading-placeholder w-full h-4 rounded"></div>
                <div class="loading-placeholder w-2/3 h-4 rounded"></div>
            </div>
        </div>
    </main>
</div>

<style>
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: rgba(0, 194, 255, 0.25);
        border-radius: 6px;
    }

    .sidebar-collapsed {
        width: 5rem !important;
        padding: 1.5rem 0.75rem !important;
    }

    .sidebar-collapsed #course-title {
        display: none !important;
    }

    .sidebar-collapsed #lesson-list button span.lesson-title {
        display: none !important;
    }

    .sidebar-collapsed #lesson-list button span.lesson-number {
        display: inline-block !important;
        width: 100%;
        text-align: center;
    }

    /* Tooltip */
    .tooltip {
        position: absolute;
        left: 4.4rem;
        top: 50%;
        transform: translateY(-50%) translateX(5px);
        background: rgba(8, 10, 20, 0.9);
        color: #E6EDF7;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        opacity: 0;
        pointer-events: none;
        white-space: nowrap;
        transition: all 0.25s ease;
        border: 1px solid rgba(0, 194, 255, 0.2);
        z-index: 50;
    }

    .sidebar-collapsed #lesson-list button:hover .tooltip {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    #course-logo::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 3.8rem;
        top: 50%;
        transform: translateY(-50%) translateX(5px);
        background: rgba(8, 10, 20, 0.9);
        color: #E6EDF7;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        opacity: 0;
        pointer-events: none;
        white-space: nowrap;
        transition: all 0.25s ease;
        border: 1px solid rgba(0, 194, 255, 0.2);
        z-index: 50;
    }

    .sidebar-collapsed #course-logo:hover::after {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    /* Lesson buttons */
    #lesson-list button {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #A1A9C4;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.25s ease;
        position: relative;
    }

    #lesson-list button:hover {
        background: rgba(0, 194, 255, 0.08);
        border-color: rgba(0, 194, 255, 0.2);
        color: #E6EDF7;
        transform: translateX(2px);
    }

    .lesson-active {
        background: rgba(0, 194, 255, 0.12);
        border-color: rgba(0, 194, 255, 0.45);
        color: #E6EDF7;
        font-weight: 600;
        box-shadow: inset 0 0 12px rgba(0, 194, 255, 0.25);
        position: relative;
    }

    .lesson-active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #00C2FF;
        border-radius: 2px;
        box-shadow: 0 0 8px rgba(0, 194, 255, 0.6);
    }

    .lesson-number {
        font-weight: 600;
        color: #00C2FF;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 1; }
    }

    .loading-placeholder {
        background: rgba(255, 255, 255, 0.06);
        animation: pulse 1.5s ease-in-out infinite;
    }

    video {
        border-radius: 18px;
        outline: none;
        box-shadow: 0 0 20px rgba(0, 194, 255, 0.2);
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
        const toggleBtn = document.getElementById('sidebarToggle');
        let activeLessonId = null;

        // Sidebar toggle
        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
        });

        // Load lessons list
        fetch(`/courses/${courseId}/lessons/data`)
            .then(res => res.json())
            .then(lessons => {
                if (!lessons.length) {
                    lessonTitle.textContent = "No lessons available.";
                    return;
                }

                lessons.forEach((lesson, index) => {
                    const li = document.createElement('li');
                    const btn = document.createElement('button');
                    btn.setAttribute('data-lesson-id', lesson.id);
                    btn.innerHTML = `
                        <span class="lesson-number">${index + 1}</span>
                        <span class="lesson-title truncate">${lesson.title}</span>
                        <span class="tooltip">${lesson.title}</span>
                    `;

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

        // Load selected lesson
        function loadLesson(lessonId, title) {
            if (lessonId === activeLessonId) return;
            activeLessonId = lessonId;

            lessonTitle.textContent = title;
            lessonContent.innerHTML = `
                <div class="loading-placeholder w-3/4 h-4 rounded"></div>
                <div class="loading-placeholder w-full h-4 rounded"></div>
                <div class="loading-placeholder w-2/3 h-4 rounded"></div>
            `;

            fetch(`/lessons/${lessonId}/stream`)
                .then(response => {
                    const contentType = response.headers.get('Content-Type');
                    if (contentType.includes('application/json')) return response.json();

                    lessonContent.innerHTML = `
                        <div class="flex justify-center items-center mt-8 px-4">
                            <div class="relative w-full max-w-5xl h-[65vh] rounded-3xl overflow-hidden border border-[#26304D] bg-[#0A0E19]/80 shadow-[0_0_25px_rgba(0,194,255,0.15)] transition-all duration-300">
                                <video 
                                    class="w-full h-full object-contain bg-black rounded-3xl" 
                                    controls 
                                    playsinline>
                                    <source src="/lessons/${lessonId}/stream" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>`;
                })
                .then(data => {
                    if (!data) return;
                    if (data.content_type === 'text') {
                        lessonContent.innerHTML = `
                            <div class="text-[#A1A9C4] leading-relaxed">
                                ${data.text_content}
                            </div>`;
                    }
                })
                .catch(() => {
                    lessonContent.innerHTML = `
                        <p class="text-red-400">Error loading lesson content.</p>`;
                });
        }
    });
</script>
@endsection
