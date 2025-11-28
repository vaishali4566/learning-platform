@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#101727] text-[#E6EDF7] py-10 px-6">

    <div class="max-w-5xl mx-auto">

        <!-- Title -->
        <div class="mb-6">
            <h3 class="text-3xl font-bold">{{ $test->title }}</h3>

            <p class="mt-1 text-[#8A93A8]">
                Total Questions: {{ $questions->count() }}
            </p>
        </div>

        <!-- Table Container -->
        <div class="bg-[#1C2541] rounded-xl border border-[#3A6EA5]/30 shadow-lg overflow-hidden">

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#101727] text-[#E6EDF7] border-b border-[#3A6EA5]/30">
                        <th class="py-4 px-4 text-left">#</th>
                        <th class="py-4 px-4 text-left">Question</th>
                        <th class="py-4 px-4 text-left">A</th>
                        <th class="py-4 px-4 text-left">B</th>
                        <th class="py-4 px-4 text-left">C</th>
                        <th class="py-4 px-4 text-left">D</th>
                        <th class="py-4 px-4 text-left">Correct</th>
                        <th class="py-4 px-4 text-left">Source</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($questions as $q)
                    <tr class="border-b border-[#3A6EA5]/20 hover:bg-[#101727]/40 transition">

                        <td class="py-4 px-4">{{ $loop->iteration }}</td>
                        <td class="py-4 px-4">{{ $q->question_text }}</td>

                        <td class="py-4 px-4">{{ $q->option_a }}</td>
                        <td class="py-4 px-4">{{ $q->option_b }}</td>
                        <td class="py-4 px-4">{{ $q->option_c }}</td>
                        <td class="py-4 px-4">{{ $q->option_d }}</td>

                        <td class="py-4 px-4 font-semibold text-[#00C2FF]">
                            {{ strtoupper($q->correct_option) }}
                        </td>
                        
                        <td class="py-4 px-4">{{ $q->source }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
