@extends('layouts.trainer.index')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-[#0a0f18] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-semibold bg-gradient-to-r from-[#00E0FF] to-[#0066FF] bg-clip-text text-transparent drop-shadow-lg">
            Lessons — {{ $course->title }}
        </h1>

        <div class="flex items-center gap-3">
            
            <a href="{{ route('trainer.courses.index') }}"
                class="px-4 py-2 rounded-xl backdrop-blur-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 hover:text-white transition-all">
                ← Back
            </a>

            @if($lessons->count())
                <a href="{{ route('trainer.courses.lessons.view', [$course->id, $lessons->first()->id]) }}"
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#26C281] to-[#1ABC9C] text-white hover:scale-105 transition-all shadow-md">
                    Explore
                </a>
            @endif

            <button id="openCreateModal"
                class="px-5 py-2 rounded-xl font-medium bg-gradient-to-r from-[#007BFF] to-[#33A1FD] hover:scale-105 transition shadow-lg">
                + Add Lesson
            </button>
        </div>
    </div>

    <!-- Lessons Table -->
    @if($lessons->count())
    <div class="bg-[#0d1627]/60 border border-[#1b2a44] rounded-2xl shadow-xl backdrop-blur-md">
        <table class="w-full text-sm">
            <thead class="bg-[#101a33] text-[#8FA2C1] uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">#</th>
                    <th class="px-6 py-3 text-left font-semibold">Title</th>
                    <th class="px-6 py-3 text-left font-semibold">Type</th>
                    <th class="px-6 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody id="lessonsTbody" class="divide-y divide-[#1b2a44]">
                @foreach($lessons as $index => $lesson)
                <tr id="lesson-row-{{ $lesson->id }}" class="hover:bg-[#15203b] transition-all duration-200">
                    <td class="px-6 py-3 text-gray-400">{{ $index+1 }}</td>
                    <td class="px-6 py-3 font-medium text-white">
                        <div class="lesson-data"
                            data-id="{{ $lesson->id }}"
                            data-title="{{ e($lesson->title) }}"
                            data-content_type="{{ $lesson->content_type }}">
                            {{ $lesson->title }}
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-300">{{ ucfirst($lesson->content_type) }}</td>

                    <td class="relative px-6 py-3 text-center">
                        <div class="absolute inline-block text-left">
                        
                            <button class="lesson-action-btn px-2  text-gray-300 hover:text-white" data-id="{{ $lesson->id }}">
                                ⋮
                            </button>

                            <!-- Dropdown -->
                            <div class="lesson-dropdown absolute top-full right-0 z-50 mt-2 hidden w-52 bg-[#0F1626] 
                                        border border-[#1E2740] rounded-xl shadow-xl text-sm overflow-hidden">

                                <ul class="py-1 divide-y divide-[#1F2A44]/50">
                                    <li>
                                        <button data-id="{{ $lesson->id }}"
                                            class="update-lesson-btn w-full text-left px-4 py-2.5 text-[#D0D8E8] 
                                                hover:bg-[#1C2541] hover:text-white flex items-center gap-2 transition">
                                            <i class="bi bi-pencil-square text-[#00C2FF] text-base"></i>
                                            Edit Title
                                        </button>
                                    </li>

                                    @if($lesson->content_type == 'quiz')
                                    <li>
                                        <a href="{{ route('trainer.quizzes.index', [$course->id]) }}"
                                            class="w-full px-4 py-2.5 block text-[#D0D8E8] 
                                                hover:bg-[#1C2541] hover:text-white flex items-center gap-2 transition">
                                            <i class="bi bi-clipboard2-check text-[#2F82DB] text-base"></i>
                                            Add Quiz
                                        </a>
                                    </li>

                                    @elseif($lesson->content_type == 'practice')
                                    <li>
                                        <a href="{{ route('trainer.practice-tests.create', [$course->id, $lesson->id]) }}"
                                            class="w-full px-4 py-2.5 block text-[#D0D8E8] 
                                                hover:bg-[#1C2541] hover:text-white flex items-center gap-2 transition">
                                            <i class="bi bi-collection-play text-[#2F82DB] text-base"></i>
                                            Create Practice
                                        </a>
                                    </li>
                                    @endif

                                    <li>
                                        <button data-id="{{ $lesson->id }}"
                                            class="delete-lesson-btn w-full text-left px-4 py-2.5 text-red-400 
                                                hover:bg-[#351C20] hover:text-red-300 flex items-center gap-2 transition font-medium">
                                            <i class="bi bi-trash3 text-red-500 text-base"></i>
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-20 bg-[#0d1627]/70 border border-[#1b2a44] rounded-2xl shadow-xl max-w-lg mx-auto backdrop-blur-lg">
        <h2 class="text-2xl font-semibold mb-2 text-white">No Lessons Found</h2>
        <p class="text-gray-400 mb-6">Add your first lesson to get started.</p>

        <button id="openCreateModalEmpty"
            class="px-6 py-2 rounded-xl bg-gradient-to-r from-[#007BFF] to-[#33A1FD] text-white hover:scale-105 shadow-lg transition">
            + Add Lesson
        </button>
    </div>
    @endif

    <!-- Create / Edit Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#101a33] border border-[#26304D] rounded-2xl p-7 w-full max-w-2xl shadow-[0_0_30px_rgba(0,150,255,0.15)] relative">

            <h2 id="modalHeading" class="text-2xl font-semibold mb-5 bg-gradient-to-r from-[#00E0FF] to-[#007BFF] bg-clip-text text-transparent">
                New Lesson
            </h2>

            <form id="createLessonForm" method="POST" action="{{ route('trainer.courses.lessons.store', $course->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" id="edit_lesson_id" name="edit_lesson_id">

                <div>
                    <label class="text-gray-400 mb-1 block">Title *</label>
                    <input id="lesson_title" type="text" name="title" required class="w-full bg-[#0d1528] border border-[#26304D] text-white rounded-xl px-3 py-2 focus:border-[#00B0FF] outline-none">
                </div>

                <div id="contentTypeWrapper">
                    <label class="text-gray-400 mb-1 block">Content Type *</label>
                    <select name="content_type" id="contentType" class="w-full bg-[#0d1528] border border-[#26304D] text-white rounded-xl px-3 py-2 focus:border-[#00B0FF] outline-none">
                        <option value="">Select</option>
                        <option value="video">Video</option>
                        <option value="text">Text</option>
                        <option value="quiz">Quiz</option>
                        <option value="practice">Practice Test</option>
                    </select>
                </div>

                <div id="contentTypeDisplay" class="hidden">
                    <label class="text-gray-400 mb-1 block">Content Type</label>
                    <div class="px-3 py-2 bg-[#0d1528] border border-[#26304D] text-gray-200 rounded-xl">
                        <span id="currentTypeText"></span>
                    </div>
                    <p class="text-xs text-yellow-300 mt-2">Once created, type cannot be changed.</p>
                </div>

                <div id="videoField" class="hidden">
                    <label class="text-gray-400 mb-1">Upload Video</label>
                    <input type="file" name="video" accept="video/*" class="w-full bg-[#0d1528] border border-[#26304D] text-white rounded-xl px-3 py-2">
                </div>

                <div id="textField" class="hidden">
                    <label class="text-gray-400 mb-1">Text Content</label>
                    <textarea name="text_content" rows="5" class="w-full bg-[#0d1528] border border-[#26304D] text-white rounded-xl px-3 py-2"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="closeCreateModal"
                        class="px-5 py-2 rounded-xl border border-[#26304D] text-gray-400 hover:bg-white/10 transition">Cancel</button>

                    <button type="submit" id="createBtn"
                        class="px-6 py-2 rounded-xl bg-gradient-to-r from-[#007BFF] to-[#33A1FD] text-white font-medium hover:scale-105 shadow-lg transition">
                        <span id="createBtnText">Create Lesson</span>
                    </button>
                </div>
            </form>

            <button id="closeCreateX" class="absolute top-4 right-4 text-gray-400 hover:text-[#00B0FF] text-2xl">×</button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#101a33] border border-[#26304D] rounded-2xl p-7 w-full max-w-md text-center shadow-[0_0_30px_rgba(255,0,0,0.1)]">
            <h2 class="text-2xl font-semibold text-red-400 mb-4">Confirm Delete</h2>
            <p class="text-gray-400 mb-6">This action cannot be undone.</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete" class="px-5 py-2 border border-[#26304D] text-gray-400 rounded-xl hover:bg-white/10">
                    Cancel
                </button>
                <button id="confirmDelete" class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow">
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

        const formData = new FormData(form);
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

    // Toggle 3-dot dropdown menu
document.addEventListener("click", (e) => {
    // Close any open dropdown if clicking outside
    document.querySelectorAll(".lesson-dropdown").forEach(dd => {
        if (!dd.contains(e.target) && !dd.previousElementSibling.contains(e.target)) {
            dd.classList.add("hidden");
        }
    });

    // Open clicked dropdown
    if (e.target.matches(".lesson-action-btn")) {
        const dropdown = e.target.nextElementSibling;
        dropdown.classList.toggle("hidden");
    }
});


});
</script>


@endsection