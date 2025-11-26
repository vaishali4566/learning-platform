@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-wide bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
            My Students
        </h1>
    </div>

    @forelse($courses as $course)
    <div class="mb-10 bg-[#10172A]/70 border border-[#2F3B57] rounded-2xl shadow-lg p-6 backdrop-blur-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-[#E6EDF7]">{{ $course->title }}</h2>
            <span class="text-sm text-[#9BA3B8]">Total Students: {{ $course->purchases->count() }}</span>
        </div>

        @if($course->purchases->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#26304D] text-[#9BA3B8]">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Progress</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->purchases as $index => $purchase)
                    <tr class="hover:bg-[#1B2542]/40 transition-all duration-200">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $purchase->user->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $purchase->user->email ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            <div class="w-full bg-[#2A3555] rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] h-2.5 rounded-full" style="width: {{ $purchase->progress ?? 0 }}%"></div>
                            </div>
                            <span class="text-xs text-[#9BA3B8]">{{ $purchase->progress ?? 0 }}%</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 text-sm rounded-full
                                            @if($purchase->status === 'completed') bg-green-600/30 text-green-400
                                            @elseif($purchase->status === 'pending') bg-yellow-600/30 text-yellow-400
                                            @else bg-blue-600/30 text-blue-400
                                            @endif">
                                {{ ucfirst($purchase->status ?? 'Unknown') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-[#9BA3B8] mt-2">No students have enrolled yet.</p>
        @endif
    </div>
    @empty
    <div class="text-center text-[#9BA3B8] text-lg mt-20">
        You haven’t created any courses yet.
    </div>
    @endforelse
</div>
@endsection