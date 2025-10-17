<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-purple-100 to-blue-100 py-10">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-center text-purple-800 mb-10">My Courses</h1>

            @if($courses->isEmpty())
            <div class="bg-white p-10 rounded-lg shadow-md text-center text-gray-700">
                <h2 class="text-2xl font-semibold mb-4">No courses purchased yet 😔</h2>
                <p>Explore our catalog and find your next learning adventure!</p>
                <a href="{{ route('courses.index') }}" class="inline-block mt-6 px-6 py-2 bg-purple-600 text-white font-semibold rounded hover:bg-purple-700 transition">
                    Browse Courses
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105">
                    <div class="h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $course->image) }}')">
                    </div>
                    <div class="p-5 flex flex-col justify-between h-42">
                        <div class="flex justify-around">
                            <h2 class="text-lg font-bold text-purple-700">{{ $course->title }}</h2>
                            <div class="text-lg font-bold text-purple-700">
                                <h2>
                                    RS {{$course->price}}
                                </h2>
                            </div>
                        </div>
                        <a href="{{ route('lessons.alllesson', ['id' => $course->id, 'price' => $course->price]) }}"
                            class="mt-4 px-4 py-2 text-center bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full font-semibold hover:from-purple-600 hover:to-indigo-600 transition">
                            Buy Now
                        </a>
                        <a href="{{ route('courses.exploreNow', $course->id) }}"
                            class="mt-4 px-4 py-2 text-center bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full font-semibold hover:from-purple-600 hover:to-indigo-600 transition">
                            Explore
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <script>
        fetch('/courses/all')
            .then(res => res.json())
            .then(data => {
                console.log(data);
                const list = document.getElementById('courses-list');
                list.innerHTML = data.data.map(course => `
            <div class="mb-3">
                <h4>${course.title}</h4>
                <a href="/courses/view/${course.id}" class="btn btn-primary">View Course</a>
            </div>
        `).join('');
            });
    </script>

</body>

</html>