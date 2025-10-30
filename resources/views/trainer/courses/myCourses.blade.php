@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 animate-fade-in">
        <h1 class="text-3xl font-bold tracking-wide">
            My Courses
        </h1>
        <a href="{{ route('courses.create') }}"
           class="px-5 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-xl shadow-md hover:shadow-[#00C2FF]/40 hover:translate-y-[-1px] transition-all duration-300 ease-in-out">
           + Create New
        </a>
    </div>

    <!-- Playlist Style Course List -->
    @if(count($courses) > 0)
    <div class="bg-[#0E1625]/80 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-lg divide-y divide-[#26304D] animate-fade-in-up">
        @foreach($courses as $index => $course)
        <div class="flex items-center justify-between p-4 hover:bg-[#1A233A]/70 transition-all duration-300 ease-in-out relative group">

            <!-- Left: Thumbnail + Info -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                    class="w-20 h-20 object-cover rounded-lg border border-[#26304D] shadow-md">
                <div>
                    <h3 class="text-lg font-semibold text-[#E6EDF7] line-clamp-1">{{ $course->title }}</h3>
                    <p class="text-sm text-[#8A93A8]">Course #{{ $index + 1 }}</p>
                </div>
            </div>

            <!-- Right: 3-dot Menu -->
            <div class="relative group/menu">
                <button
                    class="text-[#E6EDF7] hover:text-[#00C2FF] p-2 rounded-full hover:bg-[#1C2541] transition-all duration-300 focus:outline-none">
                    <!-- Vertical 3 Dots Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6h.01M12 12h.01M12 18h.01" />
                    </svg>
                </button>

                <!-- Hover Dropdown -->
                <div
                    class="absolute right-0 top-10 w-48 bg-[#0E1625] border border-[#26304D] rounded-xl shadow-lg opacity-0 scale-95 transform transition-all duration-300 ease-in-out
                           group-hover/menu:opacity-100 group-hover/menu:scale-100 group-hover/menu:translate-y-1 z-50">

                    <ul class="text-sm text-[#E6EDF7] py-1">
                        <li>
                            <a href="{{ route('trainer.courses.lessons.manage', $course->id) }}"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-[#1C2541] hover:text-[#00C2FF] transition-all duration-300">
                                <!-- Book Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m8-6H4" />
                                </svg>
                                Add Lessons
                            </a>
                        </li>
                        <li>
                            <button onclick="openDeleteModal({{ $course->id }})"
                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-red-400 hover:text-red-300 hover:bg-[#1C2541] transition-all duration-300">
                                <!-- Trash Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center mt-20 text-center animate-fade-in-up">
        <div class="bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg p-10 max-w-md">
            <h2 class="text-2xl font-semibold text-[#E6EDF7] mb-2">No Courses Found</h2>
            <p class="text-[#8A93A8] mb-6">You haven’t created any courses yet. Start by creating your first course and share your knowledge!</p>
            <a href="{{ route('courses.create') }}"
                class="inline-block px-6 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-xl shadow-md hover:shadow-[#00C2FF]/40 hover:translate-y-[-1px] transition-all duration-300">
                + Create Your First Course
            </a>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-[#0E1625] border border-[#26304D] rounded-2xl p-6 w-full max-w-md text-center shadow-2xl animate-fade-in-down relative">
            <h2 class="text-2xl font-semibold mb-3 text-[#00C2FF]">Confirm Delete</h2>
            <p class="text-[#A8B3CF] mb-6">Are you sure you want to delete this course? This action cannot be undone.</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-[#26304D] text-[#A8B3CF] rounded-lg hover:bg-[#1C2541] transition-all duration-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg shadow-md hover:shadow-red-500/40 hover:translate-y-[-1px] transition-all duration-300">
                        Yes, Delete
                    </button>
                </div>
            </form>

            <button onclick="closeDeleteModal()"
                class="absolute top-4 right-4 text-[#A8B3CF] hover:text-[#00C2FF] transition-all duration-300">
                ✕
            </button>
        </div>
    </div>
</div>

<script>
    // Delete modal logic
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
