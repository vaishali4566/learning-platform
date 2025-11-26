@extends('layouts.trainer.index')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-semibold bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
            Lessons — {{ $course->title }}
        </h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('trainer.courses.index') }}"
                class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF] hover:text-white hover:bg-[#1C2541] transition-all">
                ← Back
            </a>

            <button id="openCreateModal"
                class="px-5 py-2 rounded-lg font-medium bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white">
                + Add Lesson
            </button>
        </div>
    </div>

    <!-- Lessons Table -->
    @if($lessons->count())
    <div class="bg-[#0E1625]/90 border border-[#26304D] rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#111B2D]/60 text-[#8A93A8] text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">#</th>
                    <th class="px-6 py-3 text-left font-semibold">Title</th>
                    <th class="px-6 py-3 text-left font-semibold">Type</th>
                    <th class="px-6 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1F2945]/70">
                @foreach($lessons as $index => $lesson)
                <tr class="hover:bg-[#1A233A]/60 transition-all duration-200">
                    <td class="px-6 py-3 text-[#9BA3B8]">{{ $index + 1 }}</td>
                    <td class="px-6 py-3 font-medium text-[#E6EDF7]">{{ $lesson->title }}</td>
                    <td class="px-6 py-3 text-[#A8B3CF]">{{ ucfirst($lesson->content_type) }}</td>

                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center items-center gap-2">

                            @if($lesson->content_type === 'quiz')
                                <a href="{{ route('trainer.quizzes.index', [$course->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400">
                                    Add Quiz
                                </a>

                            @elseif($lesson->content_type === 'practice')
                                <a href="{{ route('practice-tests.create', [$course->id, $lesson->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-blue-900 text-blue-300">
                                    Create Practice Test
                                </a>

                            @else
                                <a href="{{ route('trainer.courses.lessons.view', [$course->id, $lesson->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400">
                                    Explore
                                </a>
                            @endif

                            <a href="{{ route('trainer.courses.lessons.create', [$course->id, $lesson->id]) }}"
                                class="px-3 py-1.5 rounded-md bg-blue-800 text-blue-300">
                                Update
                            </a>

                            <button data-id="{{ $lesson->id }}"
                                class="delete-lesson-btn px-3 py-1.5 rounded-md bg-red-900 text-red-300">
                                Delete
                            </button>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    <div class="text-center py-20 bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg max-w-lg mx-auto">
        <h2 class="text-2xl font-semibold mb-2 text-[#E6EDF7]">No Lessons Found</h2>
        <p class="text-[#9BA3B8] mb-6">You haven’t added any lessons yet for this course.</p>
        <button id="openCreateModalEmpty"
            class="px-6 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white">
            + Add Lesson
        </button>
    </div>
    @endif

    <!-- Create Lesson Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-2xl shadow-2xl relative">
            <h2 class="text-2xl font-semibold mb-4 text-[#00C2FF]">New Lesson</h2>

            <form id="createLessonForm"
            action="{{ route('trainer.courses.lessons.store', $course->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4">

            @csrf

            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div>
                <label class="block text-[#A8B3CF] mb-1">Title *</label>
                <input type="text" name="title" required
                    class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]">
            </div>

            <div>
                <label class="block text-[#A8B3CF] mb-1">Content Type *</label>
                <select name="content_type" id="contentType"
                    class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]">
                    <option value="">-- Select --</option>
                    <option value="video">Video</option>
                    <option value="text">Text</option>
                    <option value="quiz">Quiz</option>
                    <option value="practice">Practice Test</option>
                </select>
            </div>

            <div id="videoField" class="hidden">
                <label class="block text-[#A8B3CF] mb-1">Upload Video</label>
                <input type="file" name="video" accept="video/*"
                    class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2">
            </div>

            <div id="textField" class="hidden">
                <label class="block text-[#A8B3CF] mb-1">Text Content</label>
                <textarea name="text_content" rows="4"
                    class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="closeCreateModal"
                    class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF]">
                    Cancel
                </button>

                <button type="submit" id="createBtn"
                    class="px-5 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white">
                    Create Lesson
                </button>
            </div>

        </form>


            <button id="closeCreateX"
                class="absolute top-4 right-4 text-[#9BA3B8] hover:text-[#00C2FF]">✕</button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md text-center relative">
            <h2 class="text-2xl font-semibold text-red-400">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">Are you sure you want to delete this lesson?</p>

            <div class="flex justify-center gap-4">
                <button id="cancelDelete"
                    class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF]">
                    Cancel
                </button>

                <button id="confirmDelete"
                    class="px-4 py-2 rounded-lg bg-red-700 text-white">
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", () => {

    const createModal = document.getElementById("createModal");
    const openCreateModal = document.getElementById("openCreateModal");
    const openCreateModalEmpty = document.getElementById("openCreateModalEmpty");
    const closeCreateModal = document.getElementById("closeCreateModal");
    const closeCreateX = document.getElementById("closeCreateX");
    const createForm = document.getElementById("createLessonForm");
    const createBtn = document.getElementById("createBtn");

    const lessonsTableBody = document.querySelector("tbody");

    // ========== Open / Close Modal ==========
    [openCreateModal, openCreateModalEmpty].forEach(btn => {
        if (btn) btn.onclick = () => createModal.classList.remove("hidden");
    });

    [closeCreateModal, closeCreateX].forEach(btn => {
        btn.onclick = () => createModal.classList.add("hidden");
    });

    // ========== Content Type Switch ==========
    const typeSelect = document.getElementById("contentType");
    const videoField = document.getElementById("videoField");
    const textField = document.getElementById("textField");

    typeSelect.onchange = function () {
        videoField.classList.add("hidden");
        textField.classList.add("hidden");

        if (this.value === "video") videoField.classList.remove("hidden");
        if (this.value === "text") textField.classList.remove("hidden");
    };

    // ========== DELETE MODAL ==========
    let selectedId = null;
    const deleteModal = document.getElementById("deleteModal");

    document.querySelectorAll(".delete-lesson-btn").forEach(btn => {
        btn.onclick = () => {
            selectedId = btn.dataset.id;
            deleteModal.classList.remove("hidden");
        };
    });

    document.getElementById("cancelDelete").onclick = () => deleteModal.classList.add("hidden");

    document.getElementById("confirmDelete").onclick = async () => {
        const courseId = {{ $course->id }};
        const url = `/trainer/courses/${courseId}/${selectedId}`;

        await fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        });

        location.reload();
    };

    // ========== AJAX CREATE LESSON ==========
    createForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Disable button & show loader
        createBtn.disabled = true;
        const originalText = createBtn.innerHTML;
        createBtn.innerHTML = `<span class="animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5 inline-block"></span> Creating...`;

        const formData = new FormData(createForm);

        const response = await fetch(createForm.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        // Reset button
        createBtn.disabled = false;
        createBtn.innerHTML = originalText;

        if (result.data) {
            // Close modal
            createModal.classList.add("hidden");
            createForm.reset();

            // Append new lesson to table
            const lesson = result.data;
            const row = document.createElement("tr");
            row.className = "hover:bg-[#1A233A]/60 transition-all duration-200";
            row.innerHTML = `
                <td class="px-6 py-3 text-[#9BA3B8]">${lessonsTableBody.children.length + 1}</td>
                <td class="px-6 py-3 font-medium text-[#E6EDF7]">${lesson.title}</td>
                <td class="px-6 py-3 text-[#A8B3CF]">${lesson.content_type.charAt(0).toUpperCase() + lesson.content_type.slice(1)}</td>
                <td class="px-6 py-3 text-center">
                    <div class="flex justify-center items-center gap-2">
                        <a href="#" class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400">Explore</a>
                        <a href="#" class="px-3 py-1.5 rounded-md bg-blue-800 text-blue-300">Update</a>
                        <button data-id="${lesson.id}" class="delete-lesson-btn px-3 py-1.5 rounded-md bg-red-900 text-red-300">Delete</button>
                    </div>
                </td>
            `;
            lessonsTableBody.appendChild(row);

            // Optional: re-attach delete event for the new row
            row.querySelector(".delete-lesson-btn").onclick = () => {
                selectedId = lesson.id;
                deleteModal.classList.remove("hidden");
            };
        } else {
            alert("Failed to create lesson.");
        }
    });

});
</script>


@endsection