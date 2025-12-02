@extends('layouts.admin.index')

@section('title', 'Quiz Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] px-6 py-10 text-[#E6EDF7]">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold tracking-wide">Quiz Management</h2>
            <a href="{{ route('quizzes.create') }}"
                class="bg-gradient-to-r from-[#0071BC] to-[#00C2FF] hover:opacity-90 text-white font-medium px-4 py-2 rounded-md shadow-md transition">
                + Add Quiz
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="mt-4 mb-4">
            <input type="text" id="searchUserInput" placeholder="Search quizzes..."
                class="w-full max-w-sm bg-[#121A2E] border border-[#2F3A5F] rounded-md p-2 text-[#E6EDF7] focus:ring-2 focus:ring-[#00C2FF]">
        </div>

        <!-- Quizzes Table -->
        <div class="overflow-x-auto bg-[#121A2E]/60 border border-[#1E2A45] rounded-lg shadow-lg">
            <table class="w-full text-left">
                <thead class="bg-[#1B2540]/80 border-b border-[#24304F]">
                    <tr>
                        <th class="px-6 py-3 text-sm font-medium">#</th>
                        <th class="px-6 py-3 text-sm font-medium">Lesson</th>
                        <th class="px-6 py-3 text-sm font-medium">Title</th>
                        <th class="px-6 py-3 text-sm font-medium">Total Marks</th>
                        <th class="px-6 py-3 text-sm font-medium">Passing Marks</th>
                        <th class="px-6 py-3 text-sm font-medium text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#1E2A45]/50">
                    @forelse ($quizzes as $quiz)
                        <tr class="hover:bg-[#18223C]/60 transition">
                            <td class="px-6 py-3 text-sm">{{ $quiz->id }}</td>
                            <td class="px-6 py-3 text-sm">{{ $quiz->lesson_id }}</td>
                            <td class="px-6 py-3 text-sm font-medium">{{ $quiz->title }}</td>
                            <td class="px-6 py-3 text-sm">{{ $quiz->total_marks }}</td>
                            <td class="px-6 py-3 text-sm">{{ $quiz->passing_marks }}</td>
                            <td class="px-6 py-3 text-right space-x-2">

                                <!-- Edit Button -->
                                <a href="{{ route('quizzes.edit', $quiz->id) }}"
                                   class="px-3 py-1 text-sm bg-[#2E3B63] hover:bg-[#0071BC] rounded-md">
                                   Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('quizzes.destroy', $quiz->id) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Delete this quiz?')"
                                        class="px-3 py-1 text-sm bg-red-600/70 hover:bg-red-700 rounded-md">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No quizzes found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
