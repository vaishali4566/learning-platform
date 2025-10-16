<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md overflow-y-auto p-4">
            <h2 class="text-xl font-bold text-purple-600 mb-4">{{ $course->title }}</h2>
            <ul class="space-y-2">
                @foreach($course->lessons as $lesson)
                <li>
                    <button onclick="loadLesson({{ $lesson->id }})"
                        class="w-full text-left px-3 py-2 rounded hover:bg-purple-100 transition font-medium text-gray-700"
                        id="lesson-btn-{{ $lesson->id }}">
                        📘 {{ $lesson->title }}
                    </button>
                </li>
                @endforeach
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6">
            <div id="lesson-container" class="bg-white shadow rounded-lg p-6 min-h-[300px]">
                <p class="text-gray-500 text-center">Select a lesson from the sidebar to start learning 📚</p>
            </div>
        </main>
    </div>


    <script>
        function loadLesson(lessonId) {
            const container = document.getElementById('lesson-container');
            container.innerHTML = `<p class="text-center text-gray-400">Loading lesson...</p>`;

            fetch(`/lessons/${lessonId}/stream`)
                .then(response => {
                    const contentType = response.headers.get('Content-Type');
                    if (contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // It's a video
                        container.innerHTML = `
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
                        document.getElementById('lesson-container').innerHTML = `
                        <h3 class="text-2xl font-bold text-purple-700 mb-4">Lesson Content</h3>
                        <p class="text-gray-700 leading-relaxed">${data.text_content}</p>
                    `;
                    }
                })
                .catch(err => {
                    container.innerHTML = `<p class="text-red-600">Error loading lesson content.</p>`;
                });
        }
    </script>

</body>

</html>