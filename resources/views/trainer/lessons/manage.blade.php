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
            <tbody id="lessonsTbody" class="divide-y divide-[#1F2945]/70">
                @foreach($lessons as $index => $lesson)
                <tr id="lesson-row-{{ $lesson->id }}" class="hover:bg-[#1A233A]/60 transition-all duration-200">
                    <td class="px-6 py-3 text-[#9BA3B8]">{{ $index + 1 }}</td>
                    <td class="px-6 py-3 font-medium text-[#E6EDF7]">
                        <div class="lesson-data"
                            data-id="{{ $lesson->id }}"
                            data-title="{{ e($lesson->title) }}"
                            data-content_type="{{ $lesson->content_type }}">
                            {{ $lesson->title }}
                        </div>
                    </td>
                    <td class="px-6 py-3 text-[#A8B3CF]">{{ ucfirst($lesson->content_type) }}</td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center items-center gap-2">
                            @if($lesson->content_type === 'quiz')
                                <a href="{{ route('trainer.quizzes.index', [$course->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400">Add Quiz</a>
                            @elseif($lesson->content_type === 'practice')
                                <a href="{{ route('practice-tests.create', [$course->id, $lesson->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-blue-900 text-blue-300">Create Practice Test</a>
                            @else
                                <a href="{{ route('trainer.courses.lessons.view', [$course->id, $lesson->id]) }}"
                                    class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400">Explore</a>
                            @endif

                            <button data-id="{{ $lesson->id }}"
                                class="update-lesson-btn px-3 py-1.5 rounded-md bg-blue-800 text-blue-300 hover:bg-blue-700">
                                Edit Title
                            </button>
                            <button data-id="{{ $lesson->id }}"
                                class="delete-lesson-btn px-3 py-1.5 rounded-md bg-red-900 text-red-300 hover:bg-red-800">
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

    <!-- Create / Edit Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-2xl shadow-2xl relative">
            <h2 id="modalHeading" class="text-2xl font-semibold mb-4 text-[#00C2FF]">New Lesson</h2>

            <form id="createLessonForm"
                action="{{ route('trainer.courses.lessons.store', $course->id) }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5">

                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" id="edit_lesson_id" name="edit_lesson_id" value="">

                <div>
                    <label class="block text-[#A8B3CF] mb-1">Title *</label>
                    <input type="text" id="lesson_title" name="title" required
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]">
                </div>

                <div id="contentTypeWrapper">
                    <label class="block text-[#A8B3CF] mb-1">Content Type *</label>
                    <select name="content_type" id="contentType" required
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]">
                        <option value="">-- Select Type --</option>
                        <option value="video">Video</option>
                        <option value="text">Text</option>
                        <option value="quiz">Quiz</option>
                        <option value="practice">Practice Test</option>
                    </select>
                </div>

                <div id="contentTypeDisplay" class="hidden">
                    <label class="block text-[#A8B3CF] mb-1">Content Type</label>
                    <div class="px-3 py-2 bg-[#101727] border border-[#26304D] rounded-lg text-[#E6EDF7]">
                        <span id="currentTypeText"></span>
                    </div>
                    <p class="text-xs text-yellow-400 mt-2">Content type cannot be changed after creation.</p>
                </div>

                <div id="videoField" class="hidden">
                    <label class="block text-[#A8B3CF] mb-1">Upload Video</label>
                    <input type="file" name="video" accept="video/*"
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2">
                </div>

                <div id="textField" class="hidden">
                    <label class="block text-[#A8B3CF] mb-1">Text Content</label>
                    <textarea name="text_content" rows="5"
                        class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="closeCreateModal"
                        class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF] hover:bg-[#1C2541]">
                        Cancel
                    </button>
                    <button type="submit" id="createBtn"
                        class="px-6 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium">
                        <span id="createBtnText">Create Lesson</span>
                    </button>
                </div>
            </form>

            <button id="closeCreateX" class="absolute top-4 right-4 text-[#9BA3B8] hover:text-[#00C2FF] text-2xl">×</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md text-center">
            <h2 class="text-2xl font-semibold text-red-400 mb-4">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">This action cannot be undone.</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete" class="px-5 py-2 border border-[#26304D] text-[#A8B3CF] rounded-lg hover:bg-[#1C2541]">
                    Cancel
                </button>
                <button id="confirmDelete" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const courseId = {{ $course->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const modal = document.getElementById("createModal");
    const form = document.getElementById("createLessonForm");
    const titleInput = document.getElementById("lesson_title");
    const contentTypeSelect = document.getElementById("contentType");
    const contentTypeWrapper = document.getElementById("contentTypeWrapper");
    const contentTypeDisplay = document.getElementById("contentTypeDisplay");
    const currentTypeText = document.getElementById("currentTypeText");
    const videoField = document.getElementById("videoField");
    const textField = document.getElementById("textField");
    const editLessonId = document.getElementById("edit_lesson_id");
    const modalHeading = document.getElementById("modalHeading");
    const createBtnText = document.getElementById("createBtnText");

    const deleteModal = document.getElementById("deleteModal");
    let lessonToDelete = null;

    const openCreateButtons = [
        document.getElementById("openCreateModal"),
        document.getElementById("openCreateModalEmpty")
    ];

    openCreateButtons.forEach(btn => {
    btn?.addEventListener("click", () => {
        resetModal();
        modalHeading.textContent = "New Lesson";
        createBtnText.textContent = "Create Lesson";
        contentTypeWrapper.classList.remove("hidden");
        contentTypeDisplay.classList.add("hidden");

        contentTypeSelect.setAttribute("required", "required");

        modal.classList.remove("hidden");
    });
});

document.addEventListener("click", (e) => {
    if (e.target.matches(".update-lesson-btn")) {
        const id = e.target.dataset.id;
        const row = document.querySelector(`#lesson-row-${id}`);
        const titleSpan = row.querySelector(".lesson-data");

        editLessonId.value = id;
        titleInput.value = titleSpan.dataset.title;

        currentTypeText.textContent = 
            titleSpan.dataset.content_type.charAt(0).toUpperCase() + 
            titleSpan.dataset.content_type.slice(1);

        contentTypeSelect.removeAttribute("required");  
        contentTypeSelect.value = "";                   

        contentTypeWrapper.classList.add("hidden");
        contentTypeDisplay.classList.remove("hidden");
        videoField.classList.add("hidden");
        textField.classList.add("hidden");

        modalHeading.textContent = "Edit Lesson Title";
        createBtnText.textContent = "Update Title";
        
        modal.classList.remove("hidden");
    }
});

    contentTypeSelect.addEventListener("change", () => {
        const val = contentTypeSelect.value;
        videoField.classList.toggle("hidden", val !== "video");
        textField.classList.toggle("hidden", val !== "text");
    });

    document.getElementById("closeCreateX")?.addEventListener("click", () => modal.classList.add("hidden"));
    document.getElementById("closeCreateModal")?.addEventListener("click", () => modal.classList.add("hidden"));

    function resetModal() {
        form.reset();
        editLessonId.value = "";
        videoField.classList.add("hidden");
        textField.classList.add("hidden");
    }

    // CREATE + UPDATE TITLE — SINGLE HANDLER
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const isEdit = !!editLessonId.value;
        const url = isEdit 
            ? `/trainer/courses/lessons/update/${editLessonId.value}`
            : form.action;

        const formData = new FormData();
        formData.append("title", titleInput.value);

        if (isEdit) {
            formData.append("_method", "PUT");
        } else {
            formData.append("course_id", courseId);
            formData.append("content_type", contentTypeSelect.value);
        }

        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok && data.success) {
                if (isEdit) {
                    const row = document.querySelector(`#lesson-row-${editLessonId.value}`);
                    const span = row.querySelector(".lesson-data");
                    span.textContent = titleInput.value;
                    span.dataset.title = titleInput.value;
                } else {
                    location.reload();
                }
                modal.classList.add("hidden");
            } else {
                alert(data.message || "Error!");
            }

        } catch (err) {
            console.error(err);
        }
    });

    document.addEventListener("click", (e) => {
        if (e.target.matches(".delete-lesson-btn")) {
            lessonToDelete = e.target.dataset.id;
            deleteModal.classList.remove("hidden");
        }
    });

    document.getElementById("cancelDelete")?.addEventListener("click", () => {
        deleteModal.classList.add("hidden");
        lessonToDelete = null;
    });

    document.getElementById("confirmDelete")?.addEventListener("click", async () => {
        if (!lessonToDelete) return;
        try {
            const res = await fetch(`/trainer/courses/${courseId}/${lessonToDelete}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json",
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                document.getElementById(`lesson-row-${lessonToDelete}`).remove();
            } else {
                alert(data.message || "Error deleting!");
            }

        } catch (err) {
            console.error(err);
        } finally {
            deleteModal.classList.add("hidden");
        }
    });

});
</script>


@endsection