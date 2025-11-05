@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-6xl mx-auto space-y-16">

        {{-- 🎓 Purchased Courses --}}
        <div>
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
                    <i class="fa-solid fa-book-open"></i> My Purchased Courses
                </h2>
            </div>

            @if($purchasedCourses->isEmpty())
                <div class="text-center py-24 bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg">
                    <i class="fa-solid fa-circle-info text-4xl text-gray-500 mb-4"></i>
                    <p class="text-lg text-gray-400">You haven’t purchased any courses yet.</p>
                    <a href="{{ route('user.courses.index') }}" 
                       class="inline-block mt-4 px-6 py-3 bg-[#00C2FF] text-[#0B1120] rounded-lg font-semibold hover:bg-[#00AEE3] transition">
                       Explore Courses
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($purchasedCourses as $course)
                        <div class="group bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg hover:shadow-[#00C2FF]/20 hover:-translate-y-1 transition-all duration-300 p-6">
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ asset('storage/' . $course->image) }}" 
                                     alt="Course Image"
                                     class="w-20 h-20 object-cover rounded-lg border border-[#1E2B4A] shadow-md">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#E2E8F0] group-hover:text-[#00C2FF] transition">
                                        {{ $course->title }}
                                    </h3>
                                    <p class="text-sm text-gray-400 line-clamp-2">
                                        {{ Str::limit($course->description ?? 'No description available.', 70) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('user.courses.view', ['courseId' => $course->id]) }}" 
                               class="w-full inline-block text-center px-4 py-2 bg-[#00C2FF]/10 border border-[#00C2FF]/30 rounded-lg text-[#00C2FF] text-sm font-medium hover:bg-[#00C2FF]/20 transition">
                                Continue Course
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 💎 Available Courses --}}
<div>
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
            <i class="fa-solid fa-layer-group"></i> Available Courses
        </h2>
    </div>

    @if($availableCourses->isEmpty())
        <div class="text-center py-24 bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg">
            <i class="fa-solid fa-check-circle text-4xl text-green-400 mb-4"></i>
            <p class="text-lg text-gray-400">All available courses are purchased 🎉</p>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableCourses as $course)
                <div class="group bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg hover:shadow-[#00C2FF]/20 hover:-translate-y-1 transition-all duration-300 p-6">
                    <div class="flex items-center gap-4 mb-3">
                        <img src="{{ asset('storage/' . $course->image) }}" 
                             alt="Course Image"
                             class="w-20 h-20 object-cover rounded-lg border border-[#1E2B4A] shadow-md">
                        <div>
                            <h3 class="text-lg font-semibold text-[#E2E8F0] group-hover:text-[#00C2FF] transition">
                                {{ $course->title }}
                            </h3>
                            <p class="text-sm text-gray-400 line-clamp-2">
                                {{ Str::limit($course->description ?? 'No description available.', 70) }}
                            </p>
                            <span class="text-[#00C2FF] text-sm font-medium mt-1 block">₹{{ $course->price }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-2">
                        <a href="{{ route('payment.stripe', ['courseId' => $course->id]) }}" 
                           class="flex-1 text-center px-4 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                            Buy Now
                        </a>
                        <a href="{{ route('user.courses.explore', ['courseId' => $course->id]) }}"
                           class="flex-1 text-center px-4 py-2 bg-[#1C2541]/70 border border-[#00C2FF]/20 text-[#00C2FF] text-sm font-medium rounded-lg hover:bg-[#1C2541]/90 hover:border-[#00C2FF]/40 hover:scale-[1.02] transition-all duration-300">
                            Explore
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


    </div>
</div>
@endsection
