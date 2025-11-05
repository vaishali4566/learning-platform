@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-white py-10 px-6">
    <div class="max-w-3xl mx-auto bg-[#111B2A] rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold mb-6 text-center">Create New Course</h2>

        <form id="createCourseForm" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block font-semibold mb-2">Course Title</label>
                <input type="text" id="title" name="title" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block font-semibold mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block font-semibold mb-2">Price (₹)</label>
                <input type="number" step="0.01" id="price" name="price" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500" required>
            </div>

            <!-- Duration -->
            <div class="mb-4">
                <label for="duration" class="block font-semibold mb-2">Duration</label>
                <input type="text" id="duration" name="duration" placeholder="e.g. 3 hours, 2 weeks" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500">
            </div>

            <!-- Difficulty -->
            <div class="mb-4">
                <label for="difficulty" class="block font-semibold mb-2">Difficulty</label>
                <select id="difficulty" name="difficulty" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>

            <!-- Thumbnail -->
            <div class="mb-6">
                <label for="thumbnail" class="block font-semibold mb-2">Course Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                    class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                <p class="text-gray-400 text-sm mt-1">Upload an image (JPG, PNG, or JPEG)</p>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="city" class="block font-semibold mb-2">City</label>
                    <input type="text" id="city" name="city" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="country" class="block font-semibold mb-2">Country</label>
                    <input type="text" id="country" name="country" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Mode -->
            <div class="mb-4">
                <label for="is_online" class="block font-semibold mb-2">Mode</label>
                <select id="is_online" name="is_online" class="w-full p-3 rounded-lg bg-[#0D1622] border border-gray-600 focus:outline-none focus:border-blue-500">
                    <option value="1">Online</option>
                    <option value="0">Offline</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg text-white font-semibold transition-all">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('createCourseForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Include trainer_id automatically
    formData.append('trainer_id', "{{ Auth::guard('trainer')->id() }}");

    try {
        const response = await fetch("{{ route('trainer.courses.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Course Created!',
                text: 'Your new course has been added successfully.',
                showConfirmButton: false,
                timer: 1800,
                background: '#0A0E19',
                color: '#E6EDF7',
                iconColor: '#00C2FF'
            }).then(() => {
                window.location.href = "{{ route('trainer.courses.my') }}"; 
                // OR redirect to edit page:
                // window.location.href = `/trainer/courses/${data.data.id}/edit`;
            });

            form.reset();
        } else {
            let msg = 'Please check your inputs.';
            if (data.errors) {
                msg = Object.values(data.errors).flat().join('\\n');
            }
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: msg,
                background: '#0A0E19',
                color: '#E6EDF7',
                iconColor: '#FF4B4B'
            });
        }

    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Something went wrong. Please try again later.',
            background: '#0A0E19',
            color: '#E6EDF7',
            iconColor: '#FF4B4B'
        });
        console.error(err);
    }
});
</script>
@endsection
