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
        .then((response) => {
            const contentType = response.headers.get("Content-Type");
            if (contentType.includes("application/json")) {
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
        .then((data) => {
            if (!data) return;
            if (data.content_type === "text") {
                lessonContent.innerHTML = `
                            <div class="text-[#A1A9C4] leading-relaxed">
                                ${data.text_content}
                            </div>
                        `;
            }
            lessonContent.style.opacity = "1";
        })
        .catch((err) => {
            lessonContent.innerHTML = `<p class="text-red-400">Error loading lesson content.</p>`;
            lessonContent.style.opacity = "1";
        });
}
