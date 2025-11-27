@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#101727] text-[#E6EDF7] py-10 px-6">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-3xl font-bold">Practice Tests</h3>

            <a href="{{ route('trainer.practice-tests.create') }}"
               class="px-5 py-3 rounded-lg bg-[#00C2FF] text-black font-semibold shadow hover:bg-[#3A6EA5] transition">
                + Add Practice Test
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-[#1C2541] border border-[#3A6EA5]/40 rounded-lg text-[#E6EDF7]">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-[#1C2541] rounded-xl border border-[#3A6EA5]/30 shadow-lg overflow-hidden">

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#101727] text-[#E6EDF7] border-b border-[#3A6EA5]/30">
                        <th class="py-4 px-4 text-left">#</th>
                        <th class="py-4 px-4 text-left">Lesson</th>
                        <th class="py-4 px-4 text-left">Title</th>
                        <th class="py-4 px-4 text-left">Total Questions</th>
                        <th class="py-4 px-4 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tests as $test)
                    <tr class="border-b border-[#3A6EA5]/20 hover:bg-[#101727]/40 transition">
                        <td class="py-4 px-4">{{ $loop->iteration }}</td>
                        <td class="py-4 px-4">{{ $test->lesson->title ?? 'N/A' }}</td>
                        <td class="py-4 px-4">{{ $test->title }}</td>
                        <td class="py-4 px-4">{{ $test->total_questions }}</td>

                        <td class="py-4 px-4 flex gap-3">

                            <a href="{{ route('trainer.practice-tests.show', $test->id) }}"
                               class="px-4 py-2 rounded-md bg-[#3A6EA5] text-white text-sm hover:bg-[#00C2FF] hover:text-black transition">
                                View
                            </a>

                            <a href="{{ route('trainer.practice-tests.edit', $test->id) }}"
                               class="px-4 py-2 rounded-md bg-yellow-500/80 text-black text-sm hover:bg-yellow-400 transition">
                                Edit
                            </a>

                            <form action="{{ route('trainer.practice-tests.destroy', $test->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Delete this test?')"
                                    class="px-4 py-2 rounded-md bg-red-600 text-white text-sm hover:bg-red-500 transition">
                                    Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tests->links('pagination::tailwind') }}
        </div>

    </div>
</div>
@endsection
