@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-white py-10 px-6">
    <div class="max-w-2xl mx-auto bg-[#101828] rounded-xl shadow-xl border border-[#1E2B4A]/40 p-8">
        <h2 class="text-2xl font-semibold text-center text-[#00C2FF] mb-6 tracking-wide">
            <i class="fa-solid fa-plus-circle mr-2"></i> Create New Course
        </h2>

        <form id="createCourseForm" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium mb-1 text-gray-300">Course Title</label>
                <input type="text" id="title" name="title"
                    class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200"
                    required>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium mb-1 text-gray-300">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200"></textarea>
            </div>

            <!-- Price & Duration -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium mb-1 text-gray-300">Price (₹)</label>
                    <input type="number" step="0.01" id="price" name="price"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200"
                        required>
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium mb-1 text-gray-300">Duration</label>
                    <input type="text" id="duration" name="duration" placeholder="e.g. 3 hours"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200">
                </div>
            </div>

            <!-- Difficulty & Mode -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="difficulty" class="block text-sm font-medium mb-1 text-gray-300">Difficulty</label>
                    <select id="difficulty" name="difficulty"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>
                <div>
                    <label for="is_online" class="block text-sm font-medium mb-1 text-gray-300">Mode</label>
                    <select id="is_online" name="is_online"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200">
                        <option value="1">Online</option>
                        <option value="0">Offline</option>
                    </select>
                </div>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium mb-1 text-gray-300">City</label>
                    <input type="text" id="city" name="city"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200">
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium mb-1 text-gray-300">Country</label>
                    <input type="text" id="country" name="country"
                        class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none text-sm text-gray-200">
                </div>
            </div>

            <!-- Thumbnail -->
            <div>
                <label for="thumbnail" class="block text-sm font-medium mb-1 text-gray-300">Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                    class="block w-full text-sm text-gray-300 cursor-pointer file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#00C2FF]/10 file:text-[#00C2FF] file:font-medium hover:file:bg-[#00C2FF]/20 transition">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, or JPEG format</p>
            </div>

            <!-- Submit -->
            <div class="pt-4 text-center">
                <button type="submit"
                    class="w-full py-3 bg-[#00C2FF] hover:bg-[#00AEE3] text-[#0B1120] font-semibold rounded-lg transition-all text-sm shadow-md">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('createCourseForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    formData.append('trainer_id', "{{ Auth::guard('trainer')->id() }}");

    try {
        const response = await fetch("{{ route('trainer.courses.create') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Course Created!',
                text: 'Your new course has been added successfully.',
                showConfirmButton: false,
                timer: 1600,
                background: '#0A0E19',
                color: '#E6EDF7',
                iconColor: '#00C2FF'
            }).then(() => {
                window.location.href = "{{ route('trainer.courses.my') }}";
            });

            form.reset();
        } else {
            let msg = 'Please check your inputs.';
            if (data.errors) {
                msg = Object.values(data.errors).flat().join('\n');
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
