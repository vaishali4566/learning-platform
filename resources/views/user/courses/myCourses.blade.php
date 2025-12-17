@extends('layouts.user.index')

@section('content')
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
        <div class="flex items-center justify-between gap-4 flex-wrap mb-6 ml-2">
            <h2 class="text-xl 2xl:text-3xl font-semibold text-[#00C2FF]">
                <i class="fa-solid fa-cart-shopping"></i> My Purchases
            </h2>
            <a href="{{ route('user.courses.index') }}"
               class="px-4 py-2 bg-[#00C2FF] text-white rounded-lg hover:opacity-80">
                Explore Courses
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
            @foreach($purchases as $purchase)
                <div class="bg-white dark:bg-[#101D35] border dark:border-[#1E2B4A] rounded-2xl shadow p-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ $purchase->course->title }}
                    </h3>

                    

                    {{-- Progress --}}
                    <div class="mt-4 course-progress" data-course-id="{{ $purchase->course->id }}">
                        <div class="w-full bg-gray-300 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                            <div class="h-2 bg-[#00C2FF] progress-bar w-0"></div>
                        </div>
                        <p class="text-xs mt-1 progress-text text-gray-500">
                            Progress: 0%
                        </p>
                    </div>

                    <div class="mt-4 flex justify-between items-center gap-2">
                        <a href="{{ route('user.courses.view', $purchase->course->id) }}"
                           class="inline-block text-[#00C2FF] text-sm">
                            Continue →
                        </a>

                        {{-- Certificate Button --}}
                        <a href="{{ route('user.certificate.download', $purchase->course->id) }}"
                           class="inline-block mt-2 text-white bg-green-500 px-3 py-1 rounded-lg text-sm certificate-btn hidden"
                           target="_blank">
                           Download Certificate
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    function updateProgress(courseId) {
        const el = document.querySelector(`.course-progress[data-course-id="${courseId}"]`);
        if(!el) return;

        fetch(`/user/courses/${courseId}/progress`)
            .then(res => res.json())
            .then(data => {
                const progress = data.progress_percentage ?? 0;
                el.querySelector('.progress-bar').style.width = progress + '%';
                el.querySelector('.progress-text').innerText =
                    progress >= 100 ? 'Completed ✔' : `Progress: ${progress}%`;

                if(progress >= 100){
                    el.querySelector('.progress-bar').classList.add('bg-green-500');

                    // ✅ Correctly select course card
                    const courseCard = el.closest('.grid > div'); // the card wrapper in grid
                    if(courseCard){
                        const certBtn = courseCard.querySelector('.certificate-btn');
                        if(certBtn) certBtn.classList.remove('hidden');
                    }
                }
            })
            .catch(console.error);
    }


    // ✅ Initial load
    document.querySelectorAll('.course-progress').forEach(el => {
        updateProgress(el.dataset.courseId);
    });

    // ✅ Check localStorage on page load
    Object.keys(localStorage).forEach(key => {
        if (key.startsWith('course_updated_')) {
            const courseId = key.replace('course_updated_', '');
            updateProgress(courseId);
            localStorage.removeItem(key);
        }
    });

    // ✅ If opened in another tab
    window.addEventListener('storage', (event) => {
        if(event.key && event.key.startsWith('course_updated_')){
            const courseId = event.key.replace('course_updated_', '');
            updateProgress(courseId);
        }
    });
});
</script>
