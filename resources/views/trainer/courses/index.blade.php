@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] py-10 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Title -->
        <h1 class="text-2xl md:text-3xl font-semibold text-center text-[#E6EDF7] mb-10">
            Available Courses
        </h1>

        <!-- No Courses -->
        @if($courses->isEmpty())
        <div class="bg-[#10182C]/70 border border-[#1F2A44] backdrop-blur-md p-10 rounded-2xl shadow-lg text-center text-[#B8C1D8]">
            <h2 class="text-xl font-medium text-[#00C2FF] mb-3">No courses available yet</h2>
            <p class="text-[#AAB3C7] text-sm">Please check back later — new courses are coming soon.</p>
        </div>

        <!-- Course Grid -->
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-fadeIn">
            @foreach($courses as $course)
            <div
                class="group bg-[#10182C]/80 border border-[#1F2A44] backdrop-blur-md rounded-2xl overflow-hidden shadow-md hover:shadow-[0_0_15px_#00C2FF40] transition-all duration-300 hover:-translate-y-1">

                <!-- Thumbnail -->
                <div class="relative h-40 overflow-hidden">
                    <img src="{{ asset('storage/' . $course->image) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A0E19]/80 via-transparent to-transparent"></div>
                </div>

                <!-- Course Content -->
                <div class="p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-base font-medium text-[#E6EDF7] leading-snug">
                                {{ $course->title }}
                            </h2>
                            <span class="text-sm text-[#00C2FF] font-medium">₹{{ $course->price }}</span>
                        </div>
                        <p class="text-xs text-[#AAB3C7] line-clamp-2">
                            {{ Str::limit($course->description, 80, '...') }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 flex flex-col gap-2">
                        <a href="{{ route('payment.stripe', ['courseId' => $course->id]) }}"
                            class="w-full text-center px-3 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-full text-sm font-medium hover:shadow-[0_0_10px_#00C2FF70] transition-all duration-300">
                            Buy Now
                        </a>
                        <a href="{{ route('trainer.courses.explore', $course->id) }}"
                            class="w-full text-center px-3 py-2 border border-[#00C2FF] text-[#00C2FF] rounded-full text-sm font-medium hover:bg-[#00C2FF]/10 hover:shadow-[0_0_8px_#00C2FF60] transition-all duration-300">
                            Explore
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- Fade Animation -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.8s ease-out;
}
</style>
@endsection
