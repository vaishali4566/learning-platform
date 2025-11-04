@extends('layouts.trainer.index')

@section('content')
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
                class="px-5 py-2 rounded-lg font-medium bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white hover:shadow-[#00C2FF]/40 transition-all">
                + Add Lesson
            </button>
        </div>
    </div>

    <!-- Lessons Table -->
    @if($lessons->count())
        <div class="bg-[#0E1625]/90 border border-[#26304D] rounded-2xl shadow-lg overflow-hidden animate-fade-in">
            <table class="w-full text-sm">
                <thead class="bg-[#111B2D]/60 text-[#8A93A8] text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">#</th>
                        <th class="px-6 py-3 text-left font-semibold">Title</th>
                        <th class="px-6 py-3 text-left font-semibold">Type</th>
                        <th class="px-6 py-3 text-left font-semibold">Order</th>
                        <th class="px-6 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1F2945]/70">
                    @foreach($lessons as $index => $lesson)
                        <tr class="hover:bg-[#1A233A]/60 transition-all duration-200">
                            <td class="px-6 py-3 text-[#9BA3B8]">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-medium text-[#E6EDF7]">{{ $lesson->title }}</td>
                            <td class="px-6 py-3 text-[#A8B3CF]">{{ ucfirst($lesson->content_type) }}</td>
                            <td class="px-6 py-3 text-[#A8B3CF]">{{ $lesson->order_number }}</td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    @if($lesson->content_type === 'quiz')
                                        <a href="{{ route('trainer.quizzes.index', [$course->id]) }}"
                                           class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400 hover:bg-[#254534] transition-all duration-200">
                                           Add Quiz
                                        </a>
                                    @else
                                        <a href="{{ route('trainer.courses.lessons.view', [$course->id]) }}"
                                           class="px-3 py-1.5 rounded-md bg-[#1F3B2E] text-green-400 hover:bg-[#254534] transition-all duration-200">
                                           Explore
                                        </a>
                                    @endif

                                    <a href="{{ route('trainer.courses.lessons.create', [$course->id, $lesson->id]) }}"
                                       class="px-3 py-1.5 rounded-md bg-[#1E3A5F] text-[#66B2FF] hover:bg-[#274A78] transition-all duration-200">
                                       Update
                                    </a>

                                    <button data-id="{{ $lesson->id }}"
                                            class="delete-lesson-btn px-3 py-1.5 rounded-md bg-[#3A1F2F] text-red-400 hover:bg-[#4A2639] transition-all duration-200">
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
        <div class="text-center py-20 bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg max-w-lg mx-auto animate-fade-in-up">
            <h2 class="text-2xl font-semibold mb-2 text-[#E6EDF7]">No Lessons Found</h2>
            <p class="text-[#9BA3B8] mb-6">You haven’t added any lessons yet for this course.</p>
            <button id="openCreateModalEmpty"
                class="px-6 py-2 rounded-lg font-medium bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white hover:shadow-[#00C2FF]/40 transition-all">
                + Add Lesson
            </button>
        </div>
    @endif


    <!-- Create Lesson Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-2xl shadow-2xl relative animate-fade-in-down">
            <h2 class="text-2xl font-semibold mb-4 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">New Lesson</h2>
            <div id="createFlash" class="mb-4"></div>

            <form id="createLessonForm" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[#A8B3CF] mb-1">Title <sup class="text-red-500">*</sup></label>
                    <input type="text" name="title" id="modal_title" required
                           class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] outline-none transition-all">
                    <div id="modal_titleError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A8B3CF] mb-1">Content Type <sup class="text-red-500">*</sup></label>
                    <select name="content_type" id="modal_content_type" required
                            class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] outline-none transition-all">
                        <option value="">-- Select --</option>
                        <option value="video">Video</option>
                        <option value="text">Text</option>
                        <option value="quiz">Quiz</option>
                    </select>
                    <div id="modal_content_typeError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div id="modal_videoField" class="hidden">
                    <label class="block text-[#A8B3CF] mb-1">Upload Video</label>
                    <input type="file" name="video" id="modal_video" accept="video/*"
                           class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] outline-none transition-all">
                    <div id="modal_videoError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div id="modal_textField" class="hidden">
                    <label class="block text-[#A8B3CF] mb-1">Text Content</label>
                    <textarea name="text_content" id="modal_text_content" rows="4"
                              class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] outline-none transition-all"></textarea>
                    <div id="modal_text_contentError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A8B3CF] mb-1">Order Number <sup class="text-red-500">*</sup></label>
                    <input type="number" name="order_number" id="modal_order_number" required
                           class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] outline-none transition-all">
                    <div id="modal_order_numberError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" id="closeCreateModal"
                        class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF] hover:bg-[#1C2541] transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="modalSubmitBtn"
                        class="px-5 py-2 rounded-lg bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium hover:shadow-[#00C2FF]/40 transition-all">
                        Create Lesson
                    </button>
                </div>
            </form>

            <button id="closeCreateModalX"
                class="absolute top-4 right-4 text-[#9BA3B8] hover:text-[#00C2FF] transition-all">✕</button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md shadow-2xl text-center relative animate-fade-in-down">
            <h2 class="text-2xl font-semibold mb-2 text-[#00C2FF]">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">Are you sure you want to delete this lesson?</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete"
                    class="px-4 py-2 rounded-lg border border-[#26304D] text-[#A8B3CF] hover:bg-[#1C2541] transition-all">
                    Cancel
                </button>
                <button id="confirmDelete"
                    class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-600 to-red-800 text-white font-medium hover:shadow-red-500/40 transition-all">
                    Delete
                </button>
            </div>
            <button id="closeDeleteX"
                class="absolute top-4 right-4 text-[#9BA3B8] hover:text-[#00C2FF] transition-all">✕</button>
        </div>
    </div>
</div>
@endsection
