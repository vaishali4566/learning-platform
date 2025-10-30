@extends('layouts.trainer.lesson')

@section('content')
<div class="flex min-h-screen bg-gray-100" id="lesson-app" data-course-id="{{ $courseId }}">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-4 border-r shadow-lg">
        <h2 class="text-xl font-bold text-indigo-600 mb-4">📘 Lessons</h2>
        <ul id="lesson-list" class="space-y-2">
            <!-- Lessons will be injected here -->
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-auto">
        <div class="bg-white rounded-lg shadow p-6 min-h-[300px]">
            <h1 id="lesson-title" class="text-2xl font-bold text-blue-700 mb-4">Loading...</h1>
            <div id="lesson-content" class="prose max-w-none prose-indigo text-gray-800">
                <!-- Lesson content will be injected here -->
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const app = document.getElementById('lesson-app');
        const courseId = app.getAttribute('data-course-id');
        const lessonList = document.getElementById('lesson-list');
        const lessonTitle = document.getElementById('lesson-title');
        const lessonContent = document.getElementById('lesson-content');

        let activeLessonId = null;

        // Fetch all lessons
        fetch(`/courses/${courseId}/lessons`)
            .then(res => res.json())
            .then(lessons => {
                console.log("This is lessons", lessons);
                if (!lessons.length) {
                    lessonTitle.textContent = "No lessons available.";
                    return;
                }

                // Render lessons in sidebar
                lessons.forEach((lesson, index) => {
                    const li = document.createElement('li');
                    const btn = document.createElement('button');
                    btn.textContent = lesson.title;
                    btn.className = "w-full text-left px-3 py-2 rounded-md transition-all duration-200 hover:bg-indigo-100 hover:text-indigo-700";
                    btn.setAttribute('data-lesson-id', lesson.id);

                    btn.addEventListener('click', () => {
                        loadLesson(lesson.id, lesson.title);

                        // Highlight active lesson
                        document.querySelectorAll('#lesson-list button').forEach(el => {
                            el.classList.remove('bg-indigo-200', 'text-indigo-900', 'font-semibold');
                        });
                        btn.classList.add('bg-indigo-200', 'text-indigo-900', 'font-semibold');
                    });

                    li.appendChild(btn);
                    lessonList.appendChild(li);

                    // Auto-load first lesson
                    if (index === 0) {
                        btn.click();
                    }
                });
            })
            .catch(err => {
                lessonTitle.textContent = "Failed to load lessons.";
                console.error(err);
            });

        function loadLesson(lessonId, title) {
            if (lessonId === activeLessonId) return;
            activeLessonId = lessonId;

            lessonTitle.textContent = title;
            lessonContent.innerHTML = "";

            fetch(`/lessons/${lessonId}/stream`)
                .then(response => {
                    const contentType = response.headers.get('Content-Type');
                    if (contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // It's a video
                        lessonContent.innerHTML = `
                        <video class="w-full max-w-3xl mx-auto rounded" controls autoplay>
                            <source src="/lessons/${lessonId}/stream" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    `;
                    }
                })
                .then(data => {
                    if (!data) return;

                    if (data.content_type === 'text') {
                        document.getElementById('lesson-content').innerHTML = `                        
                        <p class="text-gray-700 leading-relaxed">${data.text_content}</p>
                    `;
                    }
                })
                .catch(err => {
                    lessonContent.innerHTML = `<p class="text-red-600">Error loading lesson content.</p>`;
                });
        }
    });
</script>
@endsection