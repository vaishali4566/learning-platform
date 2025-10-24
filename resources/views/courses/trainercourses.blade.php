@extends('layouts.trainercourses')

@section('content')
<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">My Courses</h1>

    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Image</th>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $index => $course)
            <tr class="border-t border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3">
                    <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="w-16 h-16 object-cover rounded" />
                </td>
                <td class="px-4 py-3">{{ $course->title }}</td>
                <td class="px-4 py-3 space-x-2">
                    <button
                        class="px-3 py-1 bg-gradient-to-r from-green-500 to-green-700 text-white rounded shadow-md hover:from-green-600 hover:to-green-800 hover:shadow-lg explore-btn"
                        data-id="{{ $course->id }}">
                        Explore
                    </button>

                    <button
                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 update-btn"
                        data-id="{{ $course->id }}"
                        data-title="{{ $course->title }}"
                        data-description="{{ $course->description }}">
                        Update
                    </button>

                    <button
                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 delete-btn"
                        data-id="{{ $course->id }}">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Update Modal -->
    <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-96" id="modalContent">
            <h2 class="text-xl font-semibold mb-4">Update Course</h2>
            <form id="updateForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="courseId" name="courseId" />
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 mb-1">Title</label>
                    <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded px-3 py-2" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
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

        // Show modal and fill form with data
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const title = this.dataset.title;
                const description = this.dataset.description;

                courseIdInput.value = id;
                titleInput.value = title;
                descriptionInput.value = description;

                updateModal.classList.remove('hidden');
            });
        });

        // Close modal
        cancelBtn.addEventListener('click', function() {
            updateModal.classList.add('hidden');
        });

        // Submit update form via fetch
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const id = courseIdInput.value;
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
                    body: JSON.stringify({
                        title,
                        description
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Update failed');
                    return response.json();
                })
                .then(data => {
                    alert(data.message || 'Course updated successfully');
                    location.reload(); // reload to show updated data
                })
                .catch(error => {
                    alert('Error updating course.');
                    console.error(error);
                });
        });

        // Delete button handler
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
                            location.reload(); // reload to update table
                        })
                        .catch(error => {
                            alert('Error deleting course.');
                            console.error(error);
                        });
                }
            });
        });

        // Optional: Close modal when clicking outside modal content
        updateModal.addEventListener('click', function(e) {
            if (e.target === this) {
                updateModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection