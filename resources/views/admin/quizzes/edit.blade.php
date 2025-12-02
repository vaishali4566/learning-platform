@extends('layouts.admin.index')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-xl bg-[#141C33]/60 backdrop-blur-md rounded-2xl p-8 shadow-lg border border-white/10">

        <h1 class="text-3xl font-bold mb-6 text-center">Edit Quiz</h1>

        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="text-sm font-semibold">Lesson ID</label>
                <input type="number" name="lesson_id" value="{{ $quiz->lesson_id }}" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Quiz Title</label>
                <input type="text" name="title" value="{{ $quiz->title }}" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Description</label>
                <textarea name="description" rows="4"
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none">{{ $quiz->description }}</textarea>
            </div>

            <div>
                <label class="text-sm font-semibold">Total Marks</label>
                <input type="number" name="total_marks" value="{{ $quiz->total_marks }}" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <div>
                <label class="text-sm font-semibold">Passing Marks</label>
                <input type="number" name="passing_marks" value="{{ $quiz->passing_marks }}" required
                    class="w-full mt-1 px-3 py-2 bg-[#0E1426] border border-white/20 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-600 outline-none" />
            </div>

            <button type="submit"
                class="w-full mt-6 bg-blue-600 hover:bg-blue-700 transition-all py-2 rounded-lg font-semibold text-white shadow-md">
                Update Quiz
            </button>
        </form>
    </div>

</div>

@endsection
