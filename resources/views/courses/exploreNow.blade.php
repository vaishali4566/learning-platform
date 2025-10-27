<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Explore Now</title>
</head>

<body>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-100 to-indigo-200 flex justify-center items-start pt-10 px-4"
        id="explore-app"
        data-course-id="{{ $courseId }}">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-8 space-y-6">
            <h1 class="text-3xl font-bold text-indigo-700" id="course-title">Loading...</h1>

            <div class="text-gray-600" id="course-bio">Please wait while we load the course details.</div>

            <div class="border-t pt-4 space-y-2">
                <p><span class="font-semibold text-gray-700">Trainer:</span> <span id="trainer-name"></span></p>
                <p><span class="font-semibold text-gray-700">Experience:</span> <span id="trainer-exp"></span> years</p>
                <p><span class="font-semibold text-gray-700">Created At:</span> <span id="created-date"></span></p>
            </div>

            <div class="pt-4">
                <button
                    id="buy-now-btn"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded transition duration-200">
                    Buy Now
                </button>

                <p id="buy-status" class="mt-3 text-sm font-medium text-green-600 hidden">Purchase successful!</p>
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
            const dateEl = document.getElementById('created-date');
            const buyBtn = document.getElementById('buy-now-btn');
            const buyStatus = document.getElementById('buy-status');

            // Fetch course data
            fetch(`/courses/${courseId}`)
                .then(res => res.json())
                .then(data => {
                    console.log("Mt data", data.data)
                    titleEl.textContent = data.data.title || 'Untitled Course';
                    bioEl.textContent = data.data.description || 'No description available.';
                    trainerNameEl.textContent = data.data.trainer.name || 'Unknown';
                    trainerExpEl.textContent = data.data.trainer.experience_years+" Years" || 'N/A';
                    dateEl.textContent = new Date(data.data.created_at).toLocaleDateString() || '';
                })
                .catch(error => {
                    titleEl.textContent = 'Failed to load course.';
                    bioEl.textContent = 'An error occurred.';
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
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Required if using web auth
                        },
                        body: JSON.stringify({
                            course_id: courseId
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Purchase failed.');
                        return res.json();
                    })
                    .then(data => {
                        buyBtn.textContent = 'Purchased ✔';
                        buyStatus.textContent = 'Purchase successful!';
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
</body>

</html>