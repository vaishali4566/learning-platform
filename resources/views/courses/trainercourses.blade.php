@extends('layouts.trainer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 animate-fade-in">
        <h1 class="text-3xl font-bold tracking-wide bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
            My Courses
        </h1>
        <a href="{{ route('courses.create') }}"
           class="px-5 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-xl shadow-md hover:shadow-[#00C2FF]/40 hover:translate-y-[-1px] transition-all duration-300 ease-in-out">
            + Create New
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-[#0E1625]/80 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-lg p-6 overflow-x-auto animate-fade-in-up">

        <table class="min-w-full text-sm border-separate border-spacing-y-2">
            <thead>
                <tr class="text-left text-[#8A93A8] text-xs uppercase tracking-wider">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $index => $course)
                <tr
                    class="bg-[#101727]/60 border border-[#26304D] rounded-xl hover:bg-[#1A233A]/80 transition-all duration-300 ease-in-out shadow-sm">
                    <td class="px-4 py-3 rounded-l-xl text-[#A8B3CF]">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                             class="w-16 h-16 object-cover rounded-lg shadow-md border border-[#26304D]" />
                    </td>
                    <td class="px-4 py-3 font-medium text-[#E6EDF7]">{{ $course->title }}</td>
                    <td class="px-4 py-3 rounded-r-xl flex items-center gap-3">
                        <!-- Explore -->
                        <button
                            class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-md hover:shadow-green-500/40 hover:translate-y-[-1px] transition-all duration-300 explore-btn"
                            data-id="{{ $course->id }}">
                            Explore
                        </button>

                        <!-- Update -->
                        <button
                            class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-md hover:shadow-blue-500/40 hover:translate-y-[-1px] transition-all duration-300 update-btn"
                            data-id="{{ $course->id }}"
                            data-title="{{ $course->title }}"
                            data-description="{{ $course->description }}">
                            Update
                        </button>

                        <!-- Delete -->
                        <button
                            class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg shadow-md hover:shadow-red-500/40 hover:translate-y-[-1px] transition-all duration-300 delete-btn"
                            data-id="{{ $course->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($courses) === 0)
        <div class="text-center text-[#8A93A8] py-8">
            No courses found. <a href="{{ route('courses.create') }}" class="text-[#00C2FF] hover:underline">Create one now</a>.
        </div>
        @endif
    </div>

    <!-- Update Modal -->
    <div id="updateModal"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-in-out">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md shadow-2xl animate-fade-in-down relative">
            <h2 class="text-2xl font-semibold mb-5 text-[#00C2FF]">Update Course</h2>

            <form id="updateForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="courseId" name="courseId" />

                <div class="mb-4">
                    <label for="title" class="block text-[#A8B3CF] mb-1">Title</label>
                    <input type="text" id="title" name="title"
                           class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none transition-all duration-300"
                           required />
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-[#A8B3CF] mb-1">Description</label>
                    <textarea id="description" name="description"
                              class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none transition-all duration-300"
                              rows="4" required></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelBtn"
                            class="px-4 py-2 rounded-lg bg-[#26304D] text-[#A8B3CF] hover:bg-[#1C2541] transition-all duration-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white hover:shadow-[#00C2FF]/40 hover:translate-y-[-1px] transition-all duration-300">
                        Update
                    </button>
                </div>
            </form>

            <!-- Close Icon -->
            <button id="closeModal"
                    class="absolute top-4 right-4 text-[#A8B3CF] hover:text-[#00C2FF] transition-all duration-300">
                ✕
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateModal = document.getElementById('updateModal');
        const updateForm = document.getElementById('updateForm');
        const courseIdInput = document.getElementById('courseId');
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const cancelBtn = document.getElementById('cancelBtn');
        const closeModal = document.getElementById('closeModal');

        // Open modal with prefilled data
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
                courseIdInput.value = this.dataset.id;
                titleInput.value = this.dataset.title;
                descriptionInput.value = this.dataset.description;
                updateModal.classList.remove('hidden');
            });
        });

        // Close modal
        [cancelBtn, closeModal].forEach(btn => {
            btn.addEventListener('click', () => updateModal.classList.add('hidden'));
        });

        // Click outside modal to close
        updateModal.addEventListener('click', function(e) {
            if (e.target === this) updateModal.classList.add('hidden');
        });

        // Submit update form
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const id = courseIdInput.value.trim();
            const title = titleInput.value.trim();
            const description = descriptionInput.value.trim();

            if (!title || !description) {
                alert('Please fill in all fields');
                return;
            }

            fetch(`/courses/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ title, description })
            })
            .then(response => {
                if (!response.ok) throw new Error('Update failed');
                return response.json();
            })
            .then(data => {
                alert(data.message || 'Course updated successfully');
                location.reload();
            })
            .catch(error => {
                alert('Error updating course.');
                console.error(error);
            });
        });

        // Delete course
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Are you sure you want to delete this course?')) {
                    fetch(`/courses/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Delete failed');
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message || 'Course deleted successfully');
                        location.reload();
                    })
                    .catch(error => {
                        alert('Error deleting course.');
                        console.error(error);
                    });
                }
            });
        });
    });
</script>
@endsection
