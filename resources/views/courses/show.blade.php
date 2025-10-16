<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>

<body>

    <div class="container">
        <h2 id="course-title">Loading Course...</h2>
        <div id="lessons-list">Loading Lessons...</div>
    </div>

    <script>
        const courseId = {{$courseId}};

        fetch(`/courses/${courseId}`)
            .then(res => res.json())
            .then(data => {
                console.log("course data", data)
                if (data.data) {
                    document.getElementById('course-title').textContent = data.data.title;
                }
            });

        fetch(`/courses/${courseId}/lessons`)
            .then(res => res.json())
            .then(data => {
                console.log(data)
                const list = document.getElementById('lessons-list');
                list.innerHTML = data.map(lesson => `
            <div class="card mb-2 p-3">
                <strong>${lesson.title}</strong>
                <a href="/lessons/view/${lesson.id}" class="btn btn-sm btn-success">View Lesson</a>
            </div>
        `).join('');
            });
    </script>



</body>

</html>