@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0B1120] text-gray-100 px-6 py-12">

    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-bold text-[#00C2FF]">My Courses</h1>
        <a href="{{ route('trainer.courses.create') }}"
            class="px-5 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-lg font-medium shadow-md hover:shadow-[#00C2FF]/40 hover:scale-[1.03] transition-all duration-300">
            + Create New
        </a>
    </div>

    <!-- Course List -->
    @if(count($courses) > 0)
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @foreach($courses as $course)
        <div class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
            <!-- Thumbnail -->
            <img
                src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/course-placeholder.png') }}"
                alt="{{ $course->title }} thumbnail"
                class="w-full h-40 object-cover rounded-xl mb-4 border border-[#1E2B4A]"
                loading="lazy" />


            <!-- Title -->
            <div>
                <h3 class="text-lg font-semibold text-white group-hover:text-[#00C2FF] transition line-clamp-1">
                    {{ $course->title }}
                </h3>
                <p class="text-sm text-gray-400 mt-1">
                    {{ Str::limit($course->description ?? 'No description available.', 80) }}
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center mt-5 border-t border-[#1E2B4A] pt-3">
                <a href="{{ route('trainer.courses.lessons.manage', $course->id) }}"
                    class="flex items-center gap-1 text-sm text-[#00C2FF] hover:underline transition">
                    <i class="fa-solid fa-book-open"></i> Manage Lessons
                </a>

                <button onclick="openDeleteModal({{ $course->id }})"
                    class="text-red-500 hover:text-red-400 transition text-sm flex items-center gap-1">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center mt-24">
        <div class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-10 inline-block shadow-md max-w-md">
            <i class="fa-solid fa-inbox text-5xl text-gray-500 mb-4"></i>
            <h2 class="text-xl font-semibold text-white mb-2">No Courses Found</h2>
            <p class="text-gray-400 mb-6">You haven’t created any courses yet. Start your journey now!</p>
            <a href="{{ route('trainer.courses.create') }}"
                class="inline-block px-6 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-lg font-medium shadow-md hover:shadow-[#00C2FF]/40 hover:scale-[1.03] transition-all duration-300">
                + Create Course
            </a>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md text-center shadow-2xl animate-fade-in relative">
            <h2 class="text-2xl font-semibold text-[#00C2FF] mb-3">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">Are you sure you want to delete this course?</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-[#26304D] text-[#A8B3CF] rounded-lg hover:bg-[#1C2541] transition-all duration-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg shadow-md hover:shadow-red-500/40 hover:scale-[1.03] transition-all duration-300">
                        Yes, Delete
                    </button>
                </div>
            </form>

            <button onclick="closeDeleteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-[#00C2FF] transition">
                ✕
            </button>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/trainer/courses/${id}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection