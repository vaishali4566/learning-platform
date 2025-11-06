@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-[#0B1120] text-gray-100 px-6 py-12 space-y-12">

    <!-- My Courses Section -->
    <div>
        <h1 class="text-3xl font-bold text-[#00C2FF] mb-6">My Courses</h1>

        @if(!$myCourses->isEmpty())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($myCourses as $course)
                <div class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">

                <!-- Thumbnail -->
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Image"
                     class="w-full h-40 object-cover rounded-xl mb-4 border border-[#1E2B4A]">

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
        @else
            <p class="text-gray-400 text-center">You haven't created any courses yet.</p>
        @endif
    </div>

    <!-- Available Courses Section -->
    <div>
        <h1 class="text-3xl font-bold text-[#00C2FF] mb-6">Available Courses</h1>

        @if(!$availableCourses->isEmpty())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($availableCourses as $course)
                <div class="bg-[#101B2E] border border-[#1E2B4A] rounded-2xl p-5 hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">

                    <!-- Thumbnail -->
                    <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                         class="w-full h-40 object-cover rounded-xl mb-4 border border-[#1E2B4A]">

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

                        <a href="{{ route('payment.stripe.trainer', ['courseId' => $course->id]) }}"
                           class="px-4 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white text-sm rounded-lg shadow-md hover:shadow-[#00C2FF]/40 hover:scale-[1.03] transition-all duration-300">
                            Buy Now
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center mt-10">
                <div class=" p-10 inline-block shadow-md max-w-lg">
                    <i class="fa-solid fa-inbox text-5xl text-gray-500 mb-4"></i>
                    <h2 class="text-xl font-semibold text-white mb-2">No Courses Available</h2>
                    <p class="text-gray-400 mb-6">There are no available courses right now. Check back later!</p>
                    
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
