@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0A0E19] text-gray-100 relative overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#0A0E19] via-[#0E1426] to-[#141C33] opacity-90"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-8">

        <!-- Left: Course Details -->
        <div class="flex-1">
            <div class="mb-6">
                <h1 class="text-4xl font-bold text-white mb-3 tracking-wide drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                    {{ $course->title }}
                </h1>
                <p class="text-[#A1A9C4] text-lg leading-relaxed">
                    {{ $course->description }}
                </p>
            </div>

            <div class="flex items-center flex-wrap gap-6 text-sm text-[#A1A9C4] border-t border-b border-white/10 py-3">
                <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> {{ $course->trainer->name }}</p>
                <p><span class="font-semibold text-[#00C2FF]">Experience:</span> {{ $course->trainer->experience_years ?? 'N/A' }} Years</p>
                <p><span class="font-semibold text-[#00C2FF]">Created:</span> {{ $course->created_at->format('d M, Y') }}</p>
            </div>

            <!-- ✅ What you'll learn -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-white mb-3">What you'll learn</h2>
                <ul class="space-y-2 text-[#A1A9C4] list-disc list-inside">
                    <li>Understand key concepts of this course in depth</li>
                    <li>Practical projects and real-world use cases</li>
                    <li>Hands-on lessons with step-by-step explanation</li>
                    <li>Lifetime access and certificate on completion</li>
                </ul>
            </div>

            <!-- ✅ About Trainer -->
            <div class="mt-10">
                <h2 class="text-2xl font-semibold text-white mb-3">About the Trainer</h2>
                <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl p-5">
                    <div class="w-16 h-16 bg-[#1C2541] rounded-full flex items-center justify-center text-2xl font-semibold text-[#00C2FF] shadow-lg">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-white">{{ $course->trainer->name }}</h3>
                        <p class="text-[#A1A9C4] text-sm">Experience: {{ $course->trainer->experience_years ?? 'N/A' }} Years</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Purchase Card -->
        <div class="w-full lg:w-80">
            <div class="sticky top-20 bg-[#101B2E]/80 backdrop-blur-lg border border-[#1E2B4A] rounded-2xl p-6 shadow-[0_0_25px_rgba(0,194,255,0.1)] hover:shadow-[0_0_30px_rgba(0,194,255,0.15)] transition-all duration-300">
                <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                    alt="Course Image" class="rounded-xl mb-4 w-full h-40 object-cover border border-[#1E2B4A]">

                <p class="text-2xl font-semibold text-white mb-2">₹{{ $course->price }}</p>
                <p class="text-[#A1A9C4] text-sm mb-4">Full lifetime access · Certificate of completion</p>

                <!-- ✅ Dynamic Button -->
                <div class="flex gap-3">
                    @if($isOwned || $isPurchased)
                        <a href="{{route('trainer.courses.lessons.view', $course->id)}}"
                           class="flex-1 text-center bg-gradient-to-r from-[#16A34A] to-[#15803D] text-white font-medium py-3 rounded-lg shadow-md hover:shadow-green-500/30 hover:scale-[1.02] transition-all duration-300">
                           Open Course
                        </a>
                    @else
                        <a href="{{route('payment.stripe.trainer', $course->id)}}"
                           class="flex-1 text-center bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium py-3 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                           Buy Now
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
