@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-white py-10 px-6">

    @php
        $isEdit = isset($test); // check mode
    @endphp

    <div class="max-w-xl mx-auto bg-[#101828] rounded-xl shadow-xl border border-[#1E2B4A]/40 p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-[#00C2FF] tracking-wide">
                @if($isEdit)
                    <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Practice Test
                @else
                    <i class="fa-solid fa-list-check mr-2"></i> Create Practice Test
                @endif
            </h2>

            <a href="{{ route('practice-tests.index') }}"
               class="px-4 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A] text-gray-300
                      hover:border-[#00C2FF] hover:text-[#00C2FF] transition-all">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-5 text-green-400 bg-green-900/20 border border-green-700 px-4 py-2 rounded-lg text-sm">
            {{ session('success') }}
        </div>
        @endif

        <!-- Main Form (Create + Edit) -->
        <form 
            action="{{ $isEdit ? route('practice-tests.update', $test->id) : route('practice-tests.store') }}" 
            method="POST"
            class="space-y-5"
        >
            @csrf

            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Lesson Select (only in create mode) -->
            @unless($isEdit)
            <div>
                <label class="block text-sm mb-1 text-gray-300">Select Lesson</label>
                <select name="lesson_id"
                    class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A]
                           focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF]
                           outline-none text-sm text-gray-200"
                    required>
                    <option value="">-- Choose Lesson --</option>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                    @endforeach
                </select>
                @error('lesson_id')
                    <small class="text-red-400">{{ $message }}</small>
                @enderror
            </div>
            @endunless

            <!-- Test Title -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">Test Title</label>
                <input 
                    type="text"
                    name="title"
                    value="{{ $isEdit ? $test->title : '' }}"
                    placeholder="Enter Practice Test Title"
                    class="w-full px-3 py-2 rounded-lg bg-[#0D1622] border border-[#1E2B4A]
                           focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF]
                           outline-none text-sm text-gray-200"
                    required>
                @error('title')
                    <small class="text-red-400">{{ $message }}</small>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full py-3 bg-[#00C2FF] hover:bg-[#00AEE3] text-[#0B1120]
                           font-semibold rounded-lg transition-all shadow-md text-sm">
                    @if($isEdit)
                        <i class="fa-solid fa-rotate mr-1"></i> Update Test
                    @else
                        <i class="fa-solid fa-check mr-1"></i> Create Test
                    @endif
                </button>
            </div>

        </form>

        <!-- Divider -->
        @if($isEdit)
        <div class="w-full h-px bg-[#1E2B4A] my-8"></div>

        <!-- Excel Upload Area (Only Edit Mode) -->
        <h3 class="text-xl font-semibold text-[#00C2FF] mb-4">
            <i class="fa-solid fa-file-excel mr-2"></i> Import Questions
        </h3>

        <form action="{{ route('practice-tests.import-questions', $test->id) }}" 
              method="POST"
              enctype="multipart/form-data"
              class="space-y-5">

            @csrf

            <div>
                <label class="block text-sm mb-1 text-gray-300">Choose Excel File (.xlsx)</label>
                <input type="file" 
                       name="file"
                       class="block w-full text-sm text-gray-300 cursor-pointer
                              file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 
                              file:bg-[#00C2FF]/10 file:text-[#00C2FF] file:font-medium 
                              hover:file:bg-[#00C2FF]/20 transition"
                       required>
                @error('file')
                    <small class="text-red-400">{{ $message }}</small>
                @enderror
            </div>

            <button class="w-full py-3 bg-[#00C2FF] hover:bg-[#00AEE3] text-[#0B1120]
                           font-semibold rounded-lg transition-all shadow-md text-sm">
                <i class="fa-solid fa-upload mr-1"></i> Upload Excel
            </button>

        </form>
        @endif

    </div>
</div>
@endsection
