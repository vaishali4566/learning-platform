@extends('layouts.admin.index')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-xl bg-[#141C33]/60 backdrop-blur-md rounded-2xl p-8 shadow-lg border border-white/10">

        <h1 class="text-3xl font-bold mb-6 text-center"> Create Quiz</h1>

        <form action="{{ route('quizzes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-semibold">Lesson ID</label>
                <input type="number" name="lesson_id" placeholder="Enter Lesson ID" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Quiz Title</label>
                <input type="text" name="title" placeholder="Enter Quiz Title" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Description</label>
                <textarea name="description" placeholder="Enter description..." rows="4"
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none"></textarea>
            </div>

            <div>
                <label class="text-sm font-semibold">Total Marks</label>
                <input type="number" name="total_marks" placeholder="Enter Total Marks" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Passing Marks</label>
                <input type="number" name="passing_marks" placeholder="Enter Passing Marks" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <button type="submit"
                class="w-full mt-6 bg-blue-600 hover:bg-blue-700 transition-all py-2 rounded-lg font-semibold text-white shadow-md">
                Save Quiz
            </button>
        </form>

    </div>

</div>

@endsection
