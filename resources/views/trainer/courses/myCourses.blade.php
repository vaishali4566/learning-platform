@extends('layouts.trainer.index')

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

    <!-- Course Table -->
    @if(count($courses) > 0)
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
                <tr class="bg-[#101727]/60 border border-[#26304D] rounded-xl hover:bg-[#1A233A]/80 transition-all duration-300 ease-in-out shadow-sm">
                    <td class="px-4 py-3 rounded-l-xl text-[#A8B3CF]">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                            class="w-16 h-16 object-cover rounded-lg shadow-md border border-[#26304D]" />
                    </td>
                    <td class="px-4 py-3 font-medium text-[#E6EDF7]">{{ $course->title }}</td>
                    <td class="px-4 py-3 rounded-r-xl flex items-center gap-3">
                        <!-- Explore -->
                        <a href="{{ route('trainer.courses.explore', $course->id) }}"
                            class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-md hover:shadow-green-500/40 hover:translate-y-[-1px] transition-all duration-300">
                            Explore
                        </a>

                        <!-- Update (Redirect to Lessons Page) -->
                        <a href="{{ route('trainer.courses.lessons.manage', $course->id) }}"
                            class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-md hover:shadow-blue-500/40 hover:translate-y-[-1px] transition-all duration-300">
                            Update
                        </a>

                        <!-- Delete -->
                        <button onclick="openDeleteModal({{ $course->id }})"
                            class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg shadow-md hover:shadow-red-500/40 hover:translate-y-[-1px] transition-all duration-300">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center mt-20 text-center animate-fade-in-up">
        <div class="bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg p-10 max-w-md">
            <div class="w-20 h-20 flex items-center justify-center rounded-full bg-gradient-to-br from-[#00C2FF]/10 to-[#2F82DB]/10 border border-[#00C2FF]/30 mb-5 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#00C2FF]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14l6.16-3.422A12.083 12.083 0 0118 13.5c0 3.59-2.91 6.5-6.5 6.5S5 17.09 5 13.5a12.083 12.083 0 01-.16-2.922L12 14z" />
                </svg>
            </div>
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
    function openDeleteModal(id) {
        console.log("course id:", id);
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