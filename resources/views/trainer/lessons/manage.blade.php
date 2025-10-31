@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Breadcrumbs -->
    <div class="flex items-center mb-6 space-x-3 text-sm text-[#8A93A8] animate-fade-in">
        <a href="{{ route('trainer.courses.index') }}" class="hover:text-[#00C2FF] transition-colors">Courses</a>
        <span>→</span>
        <span>{{ $course->title }}</span>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6 animate-fade-in">
        <h1 class="text-3xl font-bold tracking-wide ">
            Lessons — {{ $course->title }}
        </h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('trainer.courses.index') }}"
                class="px-4 py-2 bg-[#26304D] hover:bg-[#1C2541] text-[#A8B3CF] rounded-xl shadow transition-all duration-300">
                ← Back to Courses
            </a>

            <button id="openCreateModal"
                class="px-5 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-xl shadow-md hover:shadow-[#00C2FF]/40 transition-all duration-300">
                + Create New Lesson
            </button>
        </div>
    </div>

    <!-- Lessons Table or Empty State -->
    @if($lessons->count())
    <div class="bg-[#0E1625]/80 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-lg p-6 overflow-x-auto animate-fade-in-up">
        <table class="min-w-full text-sm border-separate border-spacing-y-2">
            <thead>
                <tr class="text-left text-[#8A93A8] text-xs uppercase tracking-wider">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="lessonsTableBody">
                @foreach($lessons as $index => $lesson)
                <tr id="lesson-row-{{ $lesson->id }}" class="bg-[#101727]/60 border border-[#26304D] rounded-xl hover:bg-[#1A233A]/80 transition-all duration-300 ease-in-out shadow-sm">
                    <td class="px-4 py-3 rounded-l-xl text-[#A8B3CF]">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-[#E6EDF7]">{{ $lesson->title }}</td>
                    <td class="px-4 py-3 text-[#A8B3CF]">{{ ucfirst($lesson->content_type) }}</td>
                    <td class="px-4 py-3 text-[#A8B3CF]">{{ $lesson->order_number }}</td>
                    <td class="px-4 py-3 rounded-r-xl flex items-center gap-3">
                        @if($lesson->content_type === 'quiz')
                            <a href="{{ route('trainer.quizzes.index', [$course->id]) }}"
                                class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-md hover:shadow-green-500/40 transition-all duration-300">
                                Add Quiz
                            </a>
                        @else
                            <a href="{{ route('trainer.courses.lessons.view', [$course->id]) }}"
                                class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-md hover:shadow-green-500/40 transition-all duration-300">
                                Explore
                            </a>
                        @endif


                        <a href="{{ route('trainer.courses.lessons.create', [$course->id, $lesson->id]) }}"
                            class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-md hover:shadow-blue-500/40 transition-all duration-300">
                            Update
                        </a>

                        <button class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg shadow-md hover:shadow-red-500/40 transition-all duration-300 delete-lesson-btn"
                            data-id="{{ $lesson->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center mt-20 text-center animate-fade-in-up">
        <div class="bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg p-10 max-w-md">
            <h2 class="text-2xl font-semibold text-[#E6EDF7] mb-2">No Lessons Found</h2>
            <p class="text-[#8A93A8] mb-6">No lessons have been created for this course yet. Start by adding your first lesson!</p>
            <button id="openCreateModalEmpty"
                class="inline-block px-6 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-xl shadow-md hover:shadow-[#00C2FF]/40 transition-all duration-300">
                + Create New Lesson
            </button>
        </div>
    </div>
    @endif

    <!-- Create Lesson Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-2xl shadow-2xl animate-fade-in-down relative">
            <h2 class="text-2xl font-semibold mb-4 text-[#00C2FF]">Create New Lesson</h2>

            <div id="createFlash" class="mb-4"></div>

            <form id="createLessonForm" enctype="multipart/form-data">
                @csrf
                {{-- Course ID (readonly) --}}
                <div class="mb-4">
                    <label class="block text-[#A8B3CF] mb-1">Course ID</label>
                    <input value="{{ $course->id }}" type="number" name="course_id" id="modal_course_id" readonly
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" />
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-[#A8B3CF] mb-1">Title <sup class="text-red-600">*</sup></label>
                    <input type="text" name="title" id="modal_title" required
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" />
                    <div id="modal_titleError" class="text-red-600 text-sm mt-1 error-message"></div>
                </div>

                {{-- Content Type --}}
                <div class="mb-4">
                    <label class="block text-[#A8B3CF] mb-1">Content Type <sup class="text-red-600">*</sup></label>
                    <select name="content_type" id="modal_content_type" required
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]">
                        <option value="">-- Select --</option>
                        <option value="video">Video</option>
                        <option value="text">Text</option>
                        <option value="quiz">Quiz</option>
                    </select>
                    <div id="modal_content_typeError" class="text-red-600 text-sm mt-1 error-message"></div>
                </div>

                {{-- Video --}}
                <div id="modal_videoField" class="mb-4 hidden">
                    <label class="block text-[#A8B3CF] mb-1">Video</label>
                    <input type="file" name="video" id="modal_video" accept="video/*"
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" />
                    <div id="modal_videoError" class="text-red-600 text-sm mt-1 error-message"></div>
                </div>

                {{-- Text --}}
                <div id="modal_textField" class="mb-4 hidden">
                    <label class="block text-[#A8B3CF] mb-1">Text Content</label>
                    <textarea name="text_content" id="modal_text_content" rows="4"
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]"></textarea>
                    <div id="modal_text_contentError" class="text-red-600 text-sm mt-1 error-message"></div>
                </div>

                {{-- Order --}}
                <div class="mb-4">
                    <label class="block text-[#A8B3CF] mb-1">Order Number <sup class="text-red-600">*</sup></label>
                    <input type="number" name="order_number" id="modal_order_number" required
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" />
                    <div id="modal_order_numberError" class="text-red-600 text-sm mt-1 error-message"></div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="closeCreateModal"
                        class="px-4 py-2 rounded-lg bg-[#26304D] text-[#A8B3CF] hover:bg-[#1C2541] transition-all duration-300">
                        Cancel
                    </button>

                    <button type="submit" id="modalSubmitBtn"
                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white hover:shadow-[#00C2FF]/40 transition-all duration-300">
                        Create
                    </button>
                </div>
            </form>

            <button id="closeCreateModalX"
                class="absolute top-4 right-4 text-[#A8B3CF] hover:text-[#00C2FF] transition-all duration-300">
                ✕
            </button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md text-center shadow-2xl animate-fade-in-down relative">
            <h2 class="text-2xl font-semibold mb-3 text-[#00C2FF]">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">Are you sure you want to delete this lesson? This action cannot be undone.</p>

            <div class="flex justify-center gap-4">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-[#26304D] text-[#A8B3CF] rounded-lg hover:bg-[#1C2541] transition-all duration-300">
                    Cancel
                </button>
                <button id="confirmDelete"
                    class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg hover:shadow-red-500/40 transition-all duration-300">
                    Yes, Delete
                </button>
            </div>

            <button id="closeDeleteX"
                class="absolute top-4 right-4 text-[#A8B3CF] hover:text-[#00C2FF] transition-all duration-300">
                ✕
            </button>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseId = "{{ $course->id }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ---------- Create Modal controls ----------
        const createModal = document.getElementById('createModal');
        const openCreateModalBtns = document.querySelectorAll('#openCreateModal, #openCreateModalEmpty');
        const closeCreateModal = document.getElementById('closeCreateModal');
        const closeCreateModalX = document.getElementById('closeCreateModalX');
        const modalCourseId = document.getElementById('modal_course_id');

        // Fill course id readonly
        modalCourseId.value = courseId;

        openCreateModalBtns.forEach(btn => btn?.addEventListener('click', () => {
            clearModalErrors();
            document.getElementById('createFlash').innerHTML = '';
            createModal.classList.remove('hidden');
            // ensure fields are reset
            document.getElementById('createLessonForm').reset();
            toggleModalFields(); // hide/show as per default
        }));

        [closeCreateModal, closeCreateModalX].forEach(btn => btn?.addEventListener('click', () => createModal.classList.add('hidden')));
        createModal.addEventListener('click', (e) => {
            if (e.target === createModal) createModal.classList.add('hidden');
        });

        // ---------- Delete Modal controls ----------
        const deleteModal = document.getElementById('deleteModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const closeDeleteX = document.getElementById('closeDeleteX');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        let deleteLessonId = null;

        cancelDelete?.addEventListener('click', () => deleteModal.classList.add('hidden'));
        closeDeleteX?.addEventListener('click', () => deleteModal.classList.add('hidden'));
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) deleteModal.classList.add('hidden');
        });

        // wire delete buttons
        document.querySelectorAll('.delete-lesson-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteLessonId = this.dataset.id;
                deleteModal.classList.remove('hidden');
            });
        });

        confirmDeleteBtn?.addEventListener('click', async function() {
            if (!deleteLessonId) return;
            try {
                // DELETE URL: /trainer/courses/{course}/lessons/{lesson}
                const deleteUrl = `{{ url("trainer/courses") }}/${courseId}/lessons/${deleteLessonId}`;

                const res = await fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Delete failed');

                // remove row from DOM
                const row = document.getElementById(`lesson-row-${deleteLessonId}`);
                if (row) row.remove();

                // if table empty, reload to show empty state (or you can adjust to render empty state client-side)
                const remaining = document.querySelectorAll('#lessonsTableBody tr').length;
                if (!remaining) location.reload();

                deleteModal.classList.add('hidden');
                // optional success toast — simple alert for now
                alert(data.message || 'Lesson deleted successfully');
            } catch (err) {
                console.error(err);
                alert(err.message || 'Error deleting lesson');
                deleteModal.classList.add('hidden');
            }
        });

        // ---------- Create form logic ----------
        const modalContentType = document.getElementById('modal_content_type');
        const modalVideoField = document.getElementById('modal_videoField');
        const modalTextField = document.getElementById('modal_textField');

        function toggleModalFields() {
            const val = modalContentType.value;
            modalVideoField.classList.add('hidden');
            modalTextField.classList.add('hidden');

            if (val === 'video') modalVideoField.classList.remove('hidden');
            else if (val === 'text') modalTextField.classList.remove('hidden');
            // quiz: show no extra fields
        }

        modalContentType.addEventListener('change', toggleModalFields);
        toggleModalFields();

        function clearModalErrors() {
            document.querySelectorAll('#createLessonForm .error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('#createLessonForm input, #createLessonForm select, #createLessonForm textarea').forEach(i => i.classList.remove('border-red-500'));
            document.getElementById('createFlash').innerHTML = '';
        }

        document.getElementById('createLessonForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearModalErrors();

            const form = e.target;
            const fd = new FormData(form);
            // ensure course_id is set
            fd.set('course_id', courseId);

            try {
                // POST URL: /trainer/courses/{course}/lessons
                const postUrl = `{{ url("trainer/courses") }}/${courseId}/lessons`;

                const res = await fetch(postUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: fd
                });

                const data = await res.json();

                if (res.ok) {
                    // success — close modal, reload (or append new row)
                    createModal.classList.add('hidden');
                    // simple approach: reload to reflect new lesson in list
                    location.reload();
                } else {
                    // validation errors
                    const flash = document.getElementById('createFlash');
                    flash.innerHTML = `<div class="text-sm text-red-400 mb-2">${data.message || 'Please fix errors'}</div>`;
                    if (data.errors) {
                        Object.keys(data.errors).forEach(k => {
                            const el = document.getElementById(`modal_${k}`) || document.getElementById(k);
                            const errDiv = document.getElementById(`modal_${k}Error`) || document.getElementById(`${k}Error`);
                            if (el) el.classList.add('border-red-500');
                            if (errDiv) errDiv.textContent = data.errors[k][0];
                        });
                    }
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while creating the lesson.');
            }
        });

        // Optional: If there are delete buttons added dynamically later, rebind them
        // (If you implement dynamic append instead of reload, call bindDeleteButtons() after append)
    });
</script>
@endsection