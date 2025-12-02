@extends('layouts.user.index')

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

<div class="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="lg:px-4 mx-auto">
        <div class="flex items-center justify-between gap-4 flex-wrap mb-4 lg:mb-5 2xl:mb-8 ml-2">
            <h2 class="text-xl 2xl:text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping"></i> My Purchases
            </h2>
            <a href="{{ route('user.courses.index') }}" 
               class="px-3 py-1.5 2xl:px-4 2xl:py-2 bg-[#00C2FF] text-sm 2xl:text-lg text-white dark:text-[#0B1120] rounded-lg hover:opacity-70 font-medium transition">
               Explore Courses
            </a>
        </div>

        @if($purchases->isEmpty())
            <div class="text-center py-24">
                <i class="fa-solid fa-box-open text-5xl text-gray-500 mb-4"></i>
                <p class="text-lg text-gray-400">You haven’t purchased any course yet.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
                @foreach($purchases as $purchase)
                    <div class="group bg-white dark:bg-[#101D35] border dark:border-[#1E2B4A] rounded-2xl shadow-lg transition-all duration-300 py-4 2xl:py-5 px-6">
                        <h3 class="text-base 2xl:text-xl font-semibold mb-2 text-gray-800 dark:text-[#E2E8F0] transition">
                            {{ $purchase->course->title }}
                        </h3>

                        <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400 mb-1 mt-4">
                            <span class="font-medium text-gray-500 dark:text-gray-300">Status:</span>
                            <span class="{{ $purchase->status === 'completed' ? 'text-[#16a34a] dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </p>

                        <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400 mb-1">
                            <span class="font-medium text-gray-500 dark:text-gray-300">Amount:</span> ₹{{ $purchase->payment->amount }}
                        </p>

                        <div class="mt-3">
                            <div class="w-full bg-gray-300 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                <div class="h-2 bg-[#00C2FF]" style="width: {{ $purchase->progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Progress: {{ $purchase->progress }}%</p>
                        </div>

                        <div class="mt-5 flex justify-between gap-2 flex-wrap items-center">
                            <a href="{{ route('user.courses.view', $purchase->course->id) }}" 
                               class="px-3 py-2 bg-[#00C2FF]/10 border border-[#00C2FF]/30 rounded-lg text-[#00C2FF] text-xs 2xl:text-sm font-medium hover:bg-[#00C2FF]/20 transition">
                                Continue Course
                            </a>
                            <a href="{{ route('user.courses.explore', $purchase->course->id) }}" 
                               class="text-gray-400 text-xs 2xl:text-sm hover:text-[#00C2FF] transition ml-2">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
