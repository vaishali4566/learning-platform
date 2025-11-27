@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0B1120] text-gray-100 px-6 py-12 space-y-16">

    {{-- ✅ Check if any data exists --}}
    @php
        $hasPurchased = $purchasedCourses->count() > 0;
        $hasMyCourses = $myCourses->count() > 0;
        $hasAvailable = $availableCourses->count() > 0;
    @endphp

    {{-- ✅ Section 1: Purchased Courses --}}
    @if($hasPurchased)
    <section>
        <h1 class="text-3xl font-bold text-[#00C2FF] mb-6 flex items-center gap-3">
            <i class="fa-solid fa-cart-shopping"></i> Purchased Courses ({{ $purchasedCourses->count() }})
        </h1>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($purchasedCourses as $purchase)
            <div
                class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">

                <!-- Thumbnail -->
                <img src="{{ $purchase->course->thumbnail ? asset('storage/' . $purchase->course->thumbnail) : asset('images/course-placeholder.png') }}"
                    alt="{{ $purchase->course->title }} thumbnail"
                    class="w-full h-44 object-cover rounded-t-2xl border-b border-[#1E2B4A]"
                    loading="lazy" />

                <!-- Title + Description -->
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-[#00C2FF] transition line-clamp-1">
                        {{ $purchase->course->title }}
                    </h3>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ Str::limit($purchase->course->description ?? 'No description available.', 80) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Purchased on: {{ $purchase->created_at->format('d M Y') }}</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center mt-5 border-t border-[#1E2B4A] pt-3">
                    <a href="{{ route('trainer.courses.lessons.view', ['course' => $purchase->course->id]) }}"
                        class="flex items-center gap-1 text-sm text-[#00C2FF] hover:underline transition">
                        <i class="fa-solid fa-book-open"></i> Open Course
                    </a>

                    <a href="{{ route('trainer.courses.explore', $purchase->course->id) }}"
                        class="text-gray-400 text-sm hover:text-[#00C2FF] transition">
                        View Details →
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ✅ Section 2: My Courses --}}
    @if($hasMyCourses)
    <section>
        <h1 class="text-3xl font-bold text-[#00C2FF] mb-6 flex items-center gap-3">
            <i class="fa-solid fa-layer-group"></i> My Courses ({{ $myCourses->count() }})
        </h1>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($myCourses as $course)
            <div
                class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">

                <!-- Thumbnail -->
                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/course-placeholder.png') }}"
                    alt="{{ $course->title }} thumbnail"
                    class="w-full h-44 object-cover rounded-t-2xl border-b border-[#1E2B4A]"
                    loading="lazy" />

                <!-- Title + Description -->
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-[#00C2FF] transition line-clamp-1">
                        {{ $course->title }}
                    </h3>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ Str::limit($course->description ?? 'No description available.', 80) }}
                    </p>
                    <span class="text-[#00C2FF] text-sm font-medium mt-2 block">₹{{ $course->price }}</span>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center mt-5 border-t border-[#1E2B4A] pt-3">
                    <a href="{{ route('trainer.courses.explore', $course->id) }}"
                        class="flex items-center gap-1 text-sm text-[#00C2FF] hover:underline transition">
                        <i class="fa-solid fa-eye"></i> Explore
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ✅ Section 3: Available Courses --}}
    @if($hasAvailable)
    <section>
        <h1 class="text-3xl font-bold text-[#00C2FF] mb-6 flex items-center gap-3">
            <i class="fa-solid fa-layer-group"></i> Available Courses ({{ $availableCourses->count() }})
        </h1>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($availableCourses as $course)
                {{-- Skip if course already purchased --}}
                @if(!$purchasedCourses->contains('course_id', $course->id))
                <div
                    class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">

                    <!-- Thumbnail -->
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/course-placeholder.png') }}"
                        alt="{{ $course->title }} thumbnail"
                        class="w-full h-44 object-cover rounded-t-2xl border-b border-[#1E2B4A]"
                        loading="lazy" />

                    <!-- Title + Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-[#00C2FF] transition line-clamp-1">
                            {{ $course->title }}
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ Str::limit($course->description ?? 'No description available.', 80) }}
                        </p>
                        <span class="text-[#00C2FF] text-sm font-medium mt-2 block">₹{{ $course->price }}</span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center mt-5 border-t border-[#1E2B4A] pt-3">
                        <a href="{{ route('trainer.courses.explore', $course->id) }}"
                            class="flex items-center gap-1 text-sm text-[#00C2FF] hover:underline transition">
                            <i class="fa-solid fa-eye"></i> Explore
                        </a>

                        <a href="{{ route('trainer.payment.stripe', ['courseId' => $course->id]) }}"
                            class="px-4 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white text-sm rounded-lg shadow-md hover:shadow-[#00C2FF]/40 hover:scale-[1.03] transition-all duration-300">
                            Buy Now
                        </a>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- 🚫 If no section has data --}}
    @if(!$hasPurchased && !$hasMyCourses && !$hasAvailable)
    <div class="text-center py-24 bg-[#101B2E] border border-[#1E2B4A] rounded-2xl shadow-lg">
        <i class="fa-solid fa-inbox text-5xl text-gray-500 mb-4"></i>
        <h2 class="text-xl font-semibold text-white mb-2">No Courses Found</h2>
        <p class="text-gray-400 mb-6">You don't have any purchased, created, or available courses yet.</p>
        <a href="{{ route('trainer.dashboard') }}"
            class="inline-block px-6 py-3 bg-[#00C2FF] text-[#0B1120] rounded-lg font-semibold hover:bg-[#00AEE3] transition">
            Go to Dashboard
        </a>
    </div>
    @endif

</div>
@endsection
