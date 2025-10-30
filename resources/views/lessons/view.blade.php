@extends('layouts.trainer.lesson')

@section('content')

<div class="container">
    <h2>Lesson Content</h2>
    <div id="lesson-content">Loading...</div>
</div>

<script>
    const lessonId = {
        {
            $lessonId
        }
    };

    fetch(`/lessons/${lessonId}`)
        .then(res => {
            const contentType = res.headers.get('content-Type');

            if (contentType && contentType.includes('application/json')) {
                return res.json();
            } else {
                // Assume it's video stream
                const videoHtml = `
                <video width="640" controls autoplay>
                    <source src="/lessons/${lessonId}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            `;
                document.getElementById('lesson-content').innerHTML = videoHtml;
            }
        })
        .then(data => {
            if (!data) return;

            const container = document.getElementById('lesson-content');

            if (data.content_type === 'text') {
                container.innerHTML = `<p>${data.text_content}</p>`;
            } else if (data.content_type === 'quiz') {
                container.innerHTML = data.quiz_questions.map((q, i) => `
                <div>
                    <h5>Q${i + 1}: ${q.question}</h5>
                    <ul>${q.options.map(opt => `<li>${opt}</li>`).join('')}</ul>
                </div>
            `).join('');
            } else if (data.message) {
                container.innerHTML = `<p class="text-danger">${data.message}</p>`;
            }
        });
</script>

@endsection