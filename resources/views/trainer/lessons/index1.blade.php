@extends('layouts.trainer.lesson')

@section('title', 'Lessons | E-Learning')

@section('content')
<div class="flex h-full">
    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="sidebar-expanded w-64 bg-[#1E293B] border-r border-[#26304D] text-[#E6EDF7] transition-all duration-300 ease-in-out overflow-y-auto">
        <!-- COURSE HEADER -->
        <div class="p-4 border-b border-[#26304D]">
            <h2 id="courseName" class="text-lg font-semibold tracking-wide text-[#00C2FF]"></h2>
            <p class="text-xs text-gray-400 mt-1">Course Lessons</p>
        </div>

        <!-- LESSON LIST -->
        <ul id="lessonList" class="space-y-1 p-3"></ul>
    </aside>

    <!-- MAIN CONTENT -->
    <section id="lessonContent" class="flex-1 p-6 overflow-y-auto transition-all duration-300 ease-in-out">
        <div id="lessonDisplay"
            class="flex flex-col items-center justify-center h-full text-gray-400 animate-fade-in">
            <i data-lucide="book-open" class="w-12 h-12 text-[#00C2FF]/60 mb-3"></i>
            <p class="text-lg">Select a lesson to view its content</p>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();

        const courseId = '{{ $courseId }}';
        console.log('Fetching lessons for course ID:', courseId);
        const lessonList = document.getElementById('lessonList');
        const lessonContent = document.getElementById('lessonDisplay');
        const courseNameEl = document.getElementById('courseName');

        try {
            const res = await fetch(`/courses/${courseId}/lessons`);
            const data = await res.json();
            console.log('Fetched lessons data:', data);

            courseNameEl.textContent = data.course?.title || 'Course Name';
            const lessons = data.lessons || [];

            lessonList.innerHTML = lessons.map((lesson, index) => `
            <li class="menu-item group relative flex items-center gap-3 px-4 py-2 rounded-lg cursor-pointer
                hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition-all duration-200"
                data-lesson='${JSON.stringify(lesson)}'>
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 flex items-center justify-center rounded-md bg-[#0E1625] text-sm font-semibold text-[#00C2FF] border border-[#00C2FF]/30">
                        ${index + 1}
                    </div>
                    <span class="sidebar-text text-sm font-medium">${lesson.title}</span>
                </div>
                <span class="tooltip">${lesson.title}</span>
            </li>
        `).join('');

            // Lesson click event
            document.querySelectorAll('#lessonList .menu-item').forEach(item => {
                item.addEventListener('click', () => {
                    const lesson = JSON.parse(item.dataset.lesson);
                    showLesson(lesson);
                    document.querySelectorAll('#lessonList .menu-item').forEach(i => i.classList.remove('bg-[#00C2FF]/20'));
                    item.classList.add('bg-[#00C2FF]/20');
                });
            });

        } catch (err) {
            console.error('Error fetching lessons:', err);
            lessonList.innerHTML = `<li class="text-red-400 px-4 py-2">Failed to load lessons.</li>`;
        }

        function showLesson(lesson) {
            let contentHTML = '';
            if (lesson.video_url) {
                contentHTML = `
                <div class="w-full max-w-4xl mx-auto space-y-4 animate-slide-up">
                    <h3 class="text-2xl font-semibold text-[#00C2FF]">${lesson.title}</h3>
                    <video controls class="w-full rounded-lg border border-[#26304D] shadow-lg">
                        <source src="${lesson.video_url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <p class="text-gray-300 mt-4">${lesson.description || ''}</p>
                </div>
            `;
            } else {
                contentHTML = `
                <div class="w-full max-w-3xl mx-auto animate-slide-up">
                    <h3 class="text-2xl font-semibold text-[#00C2FF] mb-4">${lesson.title}</h3>
                    <div class="text-gray-300 leading-relaxed bg-[#0E1625]/50 p-6 rounded-lg border border-[#26304D] shadow">
                        ${lesson.content || '<p>No content available.</p>'}
                    </div>
                </div>
            `;
            }

            lessonContent.innerHTML = contentHTML;
            lucide.createIcons();
        }

        // Sidebar collapse on navbar button click
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded', !collapsed);

            sidebarTexts.forEach(t => t.classList.toggle('hidden', collapsed));
        });
    });
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.4s ease-in-out;
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slide-up 0.4s ease-in-out;
    }

    #sidebar.sidebar-collapsed {
        width: 5rem;
        transition: width 0.3s ease-in-out;
    }

    #sidebar.sidebar-expanded {
        width: 16rem;
        transition: width 0.3s ease-in-out;
    }
</style>
@endsection