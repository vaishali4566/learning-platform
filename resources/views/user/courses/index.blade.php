@extends('layouts.user.index')

@section('content')
<style>
    html {
        scroll-behavior: smooth;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-20">

        {{-- ✅ Only show Purchased Courses if available --}}
        @if($purchasedCourses->count() > 0)
        <section>
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-3">
                    <i class="fa-solid fa-book-open"></i> My Purchased Courses ({{ $purchasedCourses->count() }})
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($purchasedCourses as $course)
                <div
                    class="bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-md hover:shadow-[#00C2FF]/30 hover:-translate-y-1 transition-all duration-300">
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/course-placeholder.png') }}"
                        alt="{{ $course->title }} thumbnail"
                        class="w-full h-44 object-cover rounded-t-2xl border-b border-[#1E2B4A]"
                        loading="lazy" />

                    <div class="p-5 space-y-3">
                        <h3 class="text-lg font-semibold text-[#E2E8F0] group-hover:text-[#00C2FF] transition">
                            {{ $course->title }}
                        </h3>
                        <p class="text-sm text-gray-400 line-clamp-2">
                            {{ Str::limit($course->description ?? 'No description available.', 80) }}
                        </p>

                        <a href="{{ route('user.courses.view', ['courseId' => $course->id]) }}"
                            class="block w-full text-center mt-3 px-4 py-2 bg-[#00C2FF]/10 border border-[#00C2FF]/30 rounded-lg text-[#00C2FF] text-sm font-medium hover:bg-[#00C2FF]/20 transition">
                            Continue Course
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ✅ Only show Available Courses if any --}}
        @if($availableCourses->count() > 0)
        <section id="available-courses">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-3">
                    <i class="fa-solid fa-layer-group"></i> Available Courses ({{ $availableCourses->count() }})
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($availableCourses as $course)
                <div
                    class="bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-md hover:shadow-[#00C2FF]/30 hover:-translate-y-1 transition-all duration-300">
                    
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/course-placeholder.png') }}"
                        alt="{{ $course->title }} thumbnail"
                        class="w-full h-44 object-cover rounded-t-2xl border-b border-[#1E2B4A]"
                        loading="lazy" />

                    <div class="p-5 space-y-3">
                        <h3 class="text-lg font-semibold text-[#E2E8F0] group-hover:text-[#00C2FF] transition">
                            {{ $course->title }}
                        </h3>
                        <p class="text-sm text-gray-400 line-clamp-2">
                            {{ Str::limit($course->description ?? 'No description available.', 80) }}
                        </p>
                        <span class="text-[#00C2FF] text-sm font-medium block">₹{{ $course->price }}</span>

                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('payment.stripe', ['courseId' => $course->id]) }}"
                                class="flex-1 text-center px-4 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition">
                                Buy Now
                            </a>
                            <a href="{{ route('user.courses.explore', ['courseId' => $course->id]) }}"
                                class="flex-1 text-center px-4 py-2 bg-[#1C2541]/70 border border-[#00C2FF]/20 text-[#00C2FF] text-sm font-medium rounded-lg hover:bg-[#1C2541]/90 hover:border-[#00C2FF]/40 hover:scale-[1.02] transition">
                                Explore
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 🚫 If no purchased & no available courses --}}
        @if($purchasedCourses->count() == 0 && $availableCourses->count() == 0)
        <div class="text-center py-24 bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg">
            <i class="fa-solid fa-circle-info text-4xl text-gray-500 mb-4"></i>
            <p class="text-lg text-gray-400 mb-4">No courses available at the moment.</p>
            <a href="{{ route('user.dashboard') }}"
                class="inline-block px-6 py-3 bg-[#00C2FF] text-[#0B1120] rounded-lg font-semibold hover:bg-[#00AEE3] transition">
                Go to Dashboard
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
