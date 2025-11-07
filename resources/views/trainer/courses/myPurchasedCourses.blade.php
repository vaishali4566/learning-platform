@extends('layouts.trainer.index')

@section('content')
{{-- ✅ SweetAlert for Payment Success --}}
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Payment Successful 🎉',
            text: '{{ session("success") }}',
            confirmButtonColor: '#00C2FF',
            background: '#0F172A',
            color: '#E2E8F0',
            timer: 6000,
            timerProgressBar: true,
        });
    </script>
@endif

<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
                <i class="fa-solid fa-chalkboard-user"></i> My Purchased Courses
            </h2>
            <a href="{{ route('trainer.courses.index') }}" 
               class="px-4 py-2 bg-[#00C2FF] text-[#0B1120] rounded-lg hover:bg-[#00AEE3] font-medium transition">
               Explore Courses
            </a>
        </div>

        @if($purchases->isEmpty())
            <div class="text-center py-24">
                <i class="fa-solid fa-box-open text-5xl text-gray-500 mb-4"></i>
                <p class="text-lg text-gray-400">You haven’t purchased any course yet.</p>
                <a href="{{ route('trainer.courses.index') }}" 
                   class="inline-block mt-4 px-6 py-3 bg-[#00C2FF] text-[#0B1120] rounded-lg font-semibold hover:bg-[#00AEE3] transition">
                   Browse Courses
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($purchases as $purchase)
                    <div class="group bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg hover:shadow-[#00C2FF]/20 hover:-translate-y-1 transition-all duration-300 p-6">
                        <h3 class="text-xl font-semibold mb-2 text-[#E2E8F0] group-hover:text-[#00C2FF] transition">
                            {{ $purchase->course->title }}
                        </h3>

                        <p class="text-sm text-gray-400 mb-1">
                            <span class="font-medium text-gray-300">Purchased on:</span> {{ $purchase->created_at->format('d M Y') }}
                        </p>

                        <div class="mt-5 flex justify-between items-center">
                            <a href="{{ route('trainer.courses.explore', $purchase->course->id) }}" 
                               class="px-3 py-2 bg-[#00C2FF]/10 border border-[#00C2FF]/30 rounded-lg text-[#00C2FF] text-sm font-medium hover:bg-[#00C2FF]/20 transition">
                                View Course
                            </a>
                            <a href="{{ route('trainer.courses.explore', $purchase->course->id) }}" 
                               class="text-gray-400 text-sm hover:text-[#00C2FF] transition">
                                Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
