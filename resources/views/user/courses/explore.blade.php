@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E] dark:text-gray-100 text-gray-500">

    <!-- Background Gradient -->
    {{-- <div class="absolute inset-0 bg-gradient-to-b from-[#0A0E19] via-[#0E1426] to-[#141C33] opacity-90"></div> --}}

    
    <!-- SKELETON LOADER -->
    <div id="course-skeleton" class="w-full">
        <div class="w-[95%] max-w-[1500px] mx-auto px-6 lg:px-12 py-12 flex flex-col-reverse lg:flex-row gap-12">

            <!-- LEFT -->
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-3 tracking-wide dark:drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                    Loading...
                </h1>
                <p class="text-gray-500 dark:text-[#A1A9C4] text-lg leading-relaxed mb-6">
                    Please wait while we load the course details.
                </p>

                <!-- Title -->
                <div class="animate-pulse space-y-6 mt-6"> 
                    <!-- Placeholder Title -->
                    <div class="h-8 w-3/4 bg-gray-200 dark:bg-white/5 rounded-lg"></div>
    
                    <!-- Placeholder Tags -->
                    <div class="flex gap-3 mt-3">
                        <div class="h-6 w-24 bg-gray-200 dark:bg-white/5 rounded-full"></div>
                        <div class="h-6 w-20 bg-gray-200 dark:bg-white/5 rounded-full"></div>
                        <div class="h-6 w-28 bg-gray-200 dark:bg-white/5 rounded-full"></div>
                    </div>
    
                    <!-- Placeholder Description Lines -->
                    <div class="space-y-2 mt-4">
                        <div class="h-4 w-full bg-gray-200 dark:bg-white/5 rounded"></div>
                        <div class="h-4 w-11/12 bg-gray-200 dark:bg-white/5 rounded"></div>
                        <div class="h-4 w-10/12 bg-gray-200 dark:bg-white/5 rounded"></div>
                    </div>
    
                    <!-- Placeholder Meta (Trainer / Experience / Created) -->
                    <div class="flex flex-wrap gap-6 border-t border-white/10 py-4 mt-6">
                        <div class="h-4 w-32 bg-gray-200 dark:bg-white/5 rounded"></div>
                        <div class="h-4 w-36 bg-gray-200 dark:bg-white/5 rounded"></div>
                        <div class="h-4 w-40 bg-gray-200 dark:bg-white/5 rounded"></div>
                    </div>
    
                </div>

               <div class="animate-pulse space-y-8 mt-4">

                    <!-- What You’ll Learn -->
                    <div>
                        <div class="h-6 w-48 bg-gray-200 dark:bg-white/10 rounded mb-4"></div>

                        <div class="space-y-3">
                            <div class="h-4 w-3/4 bg-gray-200 dark:bg-white/5 rounded"></div>
                            <div class="h-4 w-2/3 bg-gray-200 dark:bg-white/5 rounded"></div>
                            <div class="h-4 w-4/5 bg-gray-200 dark:bg-white/5 rounded"></div>
                            <div class="h-4 w-1/2 bg-gray-200 dark:bg-white/5 rounded"></div>
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mt-10">
                        <div class="h-6 w-48 bg-gray-200 dark:bg-white/10 rounded mb-4"></div>

                        <!-- Rating block -->
                        <div class= "bg-gray-200 dark:bg-white/5 border border-white/10 rounded-2xl p-6">
                            <div class="h-6 w-28 bg-gray-100 dark:bg-white/10 rounded"></div>
                            <div class="h-4 w-32 bg-gray-100 dark:bg-white/10 rounded mt-4"></div>

                            <!-- bars -->
                            <div class="space-y-3 mt-6">
                                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded"></div>
                                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded"></div>
                                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded"></div>
                                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded"></div>
                                <div class="h-3 bg-gray-100 dark:bg-white/10 rounded"></div>
                            </div>
                        </div>

                        <!-- Skeleton feedback items -->
                        <div class="space-y-4 mt-6">
                            <div class="h-20 bg-gray-200 dark:bg-white/5 rounded-xl"></div>
                            <div class="h-20 bg-gray-200 dark:bg-white/5 rounded-xl"></div>
                        </div>
                    </div>

                    <!-- Trainer -->
                    <div class="mt-10">
                        <div class="h-6 w-48 bg-gray-200 dark:bg-white/10 rounded mb-4"></div>
                        <div class="flex items-center gap-4 bg-gray-200 dark:bg-white/10 border border-white/10 rounded-xl p-5">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-white/10 rounded-full"></div>
                            <div class="space-y-3">
                                <div class="h-4 w-40 bg-gray-100 dark:bg-white/10 rounded"></div>
                                <div class="h-4 w-28 bg-gray-100 dark:bg-white/10 rounded"></div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full lg:w-[35%] animate-pulse max-w-[450px]">
                <div class="sticky top-20 bg-gray-200 dark:bg-[#101B2E]/80 border dark:border-[#1E2B4A] rounded-2xl p-6 space-y-4">

                    <div class="w-full aspect-[16/9] bg-gray-100 dark:bg-white/10 rounded-xl"></div>

                    <div class="h-6 w-24 bg-gray-100 dark:bg-white/10 rounded"></div>
                    <div class="h-4 w-40 bg-gray-100 dark:bg-white/10 rounded"></div>

                    <div class="w-full h-10 bg-gray-100 dark:bg-white/10 rounded-lg"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="relative hidden z-10 w-[95%] max-w-[1500px] mx-auto px-3 md:px-6 2xl:px-12 py-12 flex-col-reverse lg:flex-row gap-8 2xl:gap-16"
         id="explore-app"
         data-course-id="{{ $courseId }}">
         
        <!-- LEFT: COURSE DETAILS -->
        <div class="flex-1">
            <div id="course-final-content">

                <!-- Title + Bio -->
                <div class="mb-6">
                    <h1 class="text-2xl 2xl:text-4xl font-bold text-gray-800 dark:text-white tracking-wide dark:drop-shadow-[0_0_5px_rgba(0,194,255,0.3)]">
                        Full-Stack Web Development Mastery 2025
                    </h1>

                    <p class="text-gray-600 text-base dark:text-[#A1A9C4] 2xl:text-lg leading-relaxed mt-2">
                        Become a job-ready full-stack developer by mastering modern frontend,
                        backend and deployment workflows. Built with a hands-on, project-focused approach.
                    </p>

                    <!-- Tags -->
                    <div class="flex gap-2 flex-wrap mt-4">
                        <span class="px-3 py-1 text-xs 2xl:text-sm bg-[#00C2FF] text-white dark:bg-[#1C2541] dark:text-[#00C2FF] rounded-full border border-[#00C2FF]/40">Bestseller</span>
                        <span class="px-3 py-1 text-xs 2xl:text-sm bg-[#00C2FF] text-white dark:bg-[#1C2541] dark:text-[#00C2FF] rounded-full border border-[#00C2FF]/40">Trending</span>
                        <span class="px-3 py-1 text-xs 2xl:text-sm bg-[#00C2FF] text-white dark:bg-[#1C2541] dark:text-[#00C2FF] rounded-full border border-[#00C2FF]/40">Beginner Friendly</span>
                    </div>

                    <!-- Long Description -->
                    <p class="text-gray-600 dark:text-[#A1A9C4] leading-relaxed text-sm 2xl:text-base mt-0 whitespace-pre-line">
                        This comprehensive program takes you from absolute basics to advanced full-stack development.
                        You will build real-world scalable applications, learn best practices, and understand how large
                        systems are architected.

                        The course includes detailed lessons on responsive UI design, authentication, REST APIs,
                        deployment pipelines, and performance optimization. Interview prep, real-world coding patterns,
                        and expert tips are included in every module.

                        Updated monthly to ensure relevance with evolving industry standards.
                    </p>

                     <!-- Meta Details -->
                    <div class="mt-6 space-y-2 text-gray-600 dark:text-[#A1A9C4] text-xs 2xl:text-sm">
                        <p><span class="text-gray-600 dark:text-white font-semibold">Technology Used:</span> HTML5, CSS3, Tailwind, JavaScript, React.js, Node.js, Express.js, MongoDB, Git</p>
                        <p><span class="text-gray-600 dark:text-white font-semibold">Last Updated:</span> January 2025</p>
                        <p><span class="text-gray-600 dark:text-white font-semibold">Language:</span> English</p>
                    </div>
                </div>

                <!-- Meta (Trainer / Experience / Created) -->
                <div class="flex items-center flex-wrap gap-3 md:gap-6 text-xs 2xl:text-sm text-gray-600 dark:text-[#A1A9C4] border-t border-b border-gray-300 dark:border-white/10 py-3">
                    <p><span class="font-semibold text-[#00C2FF]">Trainer:</span> Aman Verma</p>
                    <p><span class="font-semibold text-[#00C2FF]">Experience:</span> 8 Years in Web Development</p>
                    <p><span class="font-semibold text-[#00C2FF]">Created:</span> December 10, 2024</p>
                </div>

                <!-- What You’ll Learn -->
                <div class="mt-7 md:mt-8">
                    <h2 class="text-xl 2xl:text-2xl font-semibold text-gray-800 dark:text-white mb-3">What you'll learn</h2>
                    <ul class="space-y-2 text-gray-600 dark:text-[#A1A9C4] list-disc list-inside text-sm 2xl:text-base">
                        <li>Understand full-stack concepts from basics to advanced</li>
                        <li>Build real world production-level applications</li>
                        <li>Master frontend + backend + database + deployment</li>
                        <li>Learn best coding practices used by top engineers</li>
                    </ul>
                </div>

                <!-- COURSE CURRICULUM -->
                <div class="mt-7 md:mt-8">
                    <h2 class="text-xl 2xl:text-2xl font-semibold text-gray-800 dark:text-white mb-4">Course Curriculum</h2>

                    <div class="space-y-3">

                        <!-- Accordion Item -->
                        <div class="border border-gray-300 dark:border-white/10 rounded-xl overflow-hidden">
                            <button onclick="toggleAccordion(this)" 
                                class="w-full flex items-center justify-between px-5 py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200/70 dark:hover:bg-[#ebebeb29]">

                                <span class="text-sm 2xl:text-base font-medium text-gray-800 dark:text-gray-200 line-clamp-1 text-left">
                                    Module 1: Introduction to Web Development
                                </span>

                            <div class="flex gap-4">
                                    <p class="text-xs 2xl:text-sm dark:text-gray-300 hidden md:block whitespace-nowrap">5 lectures • 30min</p>
                                    <i class="fa-solid fa-chevron-down text-gray-600 dark:text-gray-300 transition-transform"></i>
                                </div>
                            </button>

                            <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white/40 dark:bg-white/10">
                                <div class="px-5 py-3 space-y-3 text-xs 2xl:text-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">What is Web Development?</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">4:12</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">How Websites Work</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">3:50</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">Setting Up the Dev Environment</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">4:08</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item -->
                        <div class="border border-gray-300 dark:border-white/10 rounded-xl overflow-hidden">
                            <button onclick="toggleAccordion(this)"
                                class="w-full flex items-center justify-between px-5 py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200/70 dark:hover:bg-[#ebebeb29]">

                                <span class="text-sm 2xl:text-base font-medium text-gray-800 dark:text-gray-200 line-clamp-1 text-left">
                                    Module 2: HTML5 & CSS3 Fundamentals
                                </span>

                                <div class="flex gap-4">
                                    <p class="text-xs 2xl:text-sm dark:text-gray-300 hidden md:block whitespace-nowrap">6 lectures • 36min</p>
                                    <i class="fa-solid fa-chevron-down text-gray-600 dark:text-gray-300 transition-transform"></i>
                                </div>
                            </button>

                            <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white/40 dark:bg-white/10">
                                <div class="px-5 py-3 space-y-3 text-xs 2xl:text-sm">
                                    <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                                <p class="text-gray-500 dark:text-gray-300">HTML Structure, Tags & Semantic Layout</p>
                                            </div>
                                            <span class="text-gray-500 dark:text-gray-400 text-xs">10:12</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">CSS Selectors, Flexbox, Grid</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">23:50</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300"> Responsive Design Techniques</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">14:08</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item -->
                        <div class="border border-gray-300 dark:border-white/10 rounded-xl overflow-hidden">
                            <button onclick="toggleAccordion(this)"
                                class="w-full flex items-center justify-between px-5 py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200/70 dark:hover:bg-[#ebebeb29]">

                                <span class="text-sm 2xl:text-base font-medium text-gray-800 dark:text-gray-200 line-clamp-1 text-left">
                                    Module 3: JavaScript Essentials
                                </span>

                                <div class="flex gap-4">
                                    <p class="text-xs 2xl:text-sm dark:text-gray-300 hidden md:block whitespace-nowrap">4 lectures • 20min</p>
                                    <i class="fa-solid fa-chevron-down text-gray-600 dark:text-gray-300 transition-transform"></i>
                                </div>
                            </button>

                            <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white/40 dark:bg-white/10">
                                <div class="px-5 py-3 space-y-3 text-xs 2xl:text-sm">
                                    <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                                <p class="text-gray-500 dark:text-gray-300">Variables, Functions, Arrays, Objects</p>
                                            </div>
                                            <span class="text-gray-500 dark:text-gray-400 text-xs">20:12</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">DOM Manipulation & Events</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">19:50</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">ES6+ Concepts</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">24:08</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Item -->
                        <div class="border border-gray-300 dark:border-white/10 rounded-xl overflow-hidden">
                            <button onclick="toggleAccordion(this)"
                                class="w-full flex items-center justify-between px-5 py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200/70 dark:hover:bg-[#ebebeb29]">

                                <span class="text-sm 2xl:text-base font-medium text-gray-800 dark:text-gray-200 line-clamp-1 text-left">
                                    Module 4: React.js Mastery
                                </span>

                                <div class="flex gap-4">
                                    <p class="text-xs 2xl:text-sm dark:text-gray-300 hidden md:block whitespace-nowrap">12 lectures • 1hr 20min</p>
                                    <i class="fa-solid fa-chevron-down text-gray-600 dark:text-gray-300 transition-transform"></i>
                                </div>
                            </button>

                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-white/40 dark:bg-white/10">
                                <div class="px-5 py-3 space-y-3 text-xs 2xl:text-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">Components, Props, State</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">25:12</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">Hooks & Advanced Patterns</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">23:50</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <i data-lucide="monitor-play" class=" w-4 h-4 text-gray-500 dark:text-gray-300"></i>
                                            <p class="text-gray-500 dark:text-gray-300">Building Real-World UI</p>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">34:08</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ⭐ FEEDBACK SECTION -->
                <div class="mt-7 md:mt-10">
                    <h2 class="text-xl 2xl:text-2xl font-semibold text-gray-800 dark:text-white mb-6">Course Feedback</h2>

                    <!-- Rating Summary -->
                    <div class="bg-white dark:bg-white/5 shadow border border-white/10 rounded-2xl p-6 mb-6">
                        <div class="flex flex-col lg:flex-row justify-between gap-8">

                            <!-- Left: Overall Rating -->
                            <div>
                                <p class="text-4xl 2xl:text-5xl font-bold text-gray-800 dark:text-white">
                                    4.7 <span class="text-xl 2xl:text-2xl text-yellow-400 align-top">/ 5</span>
                                </p>

                                <!-- Stars -->
                                <div class="flex text-yellow-400 text-xl 2xl:text-2xl mt-1">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </div>

                                <p class="text-gray-500 dark:text-gray-400 text-xs 2xl:text-sm mt-2">Based on 152 reviews</p>
                            </div>

                            <!-- Middle: Rating Bars -->
                            <div class="flex-1">
                                <div class="space-y-2 text-xs 2xl:text-sm">

                                    <!-- 5 Stars -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600 dark:text-gray-300 w-12">5 Stars</span>
                                        <div class="bg-gray-200 dark:bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded" style="width: 78%;"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">118</span>
                                    </div>

                                    <!-- 4 Stars -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600 dark:text-gray-300 w-12">4 Stars</span>
                                        <div class="bg-gray-200 dark:bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded" style="width: 16%;"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">24</span>
                                    </div>

                                    <!-- 3 Stars -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600 dark:text-gray-300 w-12">3 Stars</span>
                                        <div class="bg-gray-200 dark:bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded" style="width: 4%;"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">7</span>
                                    </div>

                                    <!-- 2 Stars -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600 dark:text-gray-300 w-12">2 Stars</span>
                                        <div class="bg-gray-200 dark:bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded" style="width: 1%;"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">2</span>
                                    </div>

                                    <!-- 1 Star -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600 dark:text-gray-300 w-12">1 Star</span>
                                        <div class="bg-gray-200 dark:bg-gray-700 h-2 flex-1 rounded">
                                            <div class="bg-[#00C2FF] h-2 rounded" style="width: 1%;"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400">1</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Button -->
                            @if($isPurchased)
                            <div class="flex items-center">
                                <button id="open-feedback-modal"
                                    class="bg-[#00C2FF] hover:bg-[#0098CC] text-xs 2xl:text-sm text-white px-5 py-2 rounded-lg shadow">
                                    Leave Us Feedback
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- FEEDBACK LIST -->
                    <div id="feedback-list" class="space-y-4">

                        <!-- Feedback Item 1 -->
                        <div class="bg-white dark:bg-white/5 border border-white/10 rounded-xl p-5 shadow">
                            <div class="flex justify-between">
                                <p class="font-semibold text-gray-700 dark:text-white text-sm 2xl:text-base">Rahul Singh</p>
                                <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400">Jan 15, 2025</p>
                            </div>

                            <div class="flex text-yellow-400 text-base 2xl:text-lg mt-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>

                            <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm 2xl:text-base">
                                Amazing course! The projects and explanations were crystal clear. Helped me crack my first frontend job.
                            </p>
                        </div>

                        <!-- Feedback Item 2 -->
                        <div class="bg-white dark:bg-white/5 border border-white/10 rounded-xl p-5 shadow">
                            <div class="flex justify-between">
                                <p class="font-semibold text-gray-700 dark:text-white text-sm 2xl:text-base">Sneha Mirza</p>
                                <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400">Dec 28, 2024</p>
                            </div>

                            <div class="flex text-yellow-400 text-base 2xl:text-lg mt-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>

                           <p class="text-gray-600 dark:text-gray-300 mt-2 leading-relaxed line-clamp-2 text-sm 2xl:text-base">
                                A very comprehensive and well-explained course. The content is up-to-date with the latest
                                technology trends. I particularly liked the backend modules and how everything was connected
                                to real-world systems.  
                                I wish there were even more advanced projects, but overall this gave me the clarity and confidence I needed
                                to start applying for developer roles.
                            </p>
                        </div>

                        <!-- Feedback Item 3 -->
                        <div class="bg-white dark:bg-white/5 border border-white/10 rounded-xl p-5 shadow">
                            <div class="flex justify-between">
                                <p class="font-semibold text-gray-700 dark:text-white text-sm 2xl:text-base">Aditya Rao</p>
                                <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400">Nov 18, 2024</p>
                            </div>

                            <div class="flex text-yellow-400 text-base 2xl:text-lg mt-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>

                            <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm 2xl:text-base">
                                Very informative and beginner-friendly. Could use a few more advanced assignments, but overall fantastic.
                            </p>
                        </div>

                    </div>

                     <div class="mt-6">
                        <a href="/course/{{ $courseId }}/reviews"
                            class="block w-40 text-xs 2xl:text-sm text-center py-2 rounded-md border border-[#00C2FF] text-[#00c2ff] hover:text-white dark:text-white font-semibold hover:bg-[#00afd9] transition">
                            Show All Reviews
                        </a>
                    </div>
                </div>

                <!-- About Trainer -->
                <div class="mt-7 md:mt-10">
                    <h2 class="text-xl 2xl:text-2xl font-semibold text-gray-800 dark:text-white mb-4">About the Trainer</h2>

                    <div class="flex items-center gap-4 bg-white dark:bg-white/5 shadow border border-white/10 rounded-xl p-5">
                        <div class="w-10 h-10 2xl:w-16 2xl:h-16 bg-[#00c2ff] dark:bg-[#1C2541] rounded-full flex items-center justify-center text-base 2xl:text-2xl font-semibold text-white dark:text-[#00C2FF] shadow-lg">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>
                            <h3 class="text-base 2xl:text-lg font-medium text-gray-800 dark:text-white">Aman Verma</h3>
                            <p class="text-gray-500 dark:text-[#A1A9C4] text-xs 2xl:text-sm">Senior Web Developer • 8+ Years Experience</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- RIGHT: PURCHASE CARD -->
        <div class="w-full lg:w-[35%] max-w-[450px]">
            <div class="sticky top-20 bg-white dark:bg-[#101B2E]/80 backdrop-blur-lg border dark:border-[#1E2B4A] rounded-2xl p-6">

                <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                     class="rounded-xl mb-4 w-full aspect-[16/9] object-cover border dark:border-[#1E2B4A]">

                <p class="text-xl 2xl:text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                    ₹<span id="course-price">900</span>
                </p>
                <p class="text-gray-500 dark:text-[#A1A9C4] text-xs 2xl:text-sm mb-4">Full lifetime access · Certificate of completion</p>

                <div class="flex flex-col gap-2 text-xs 2xl:text-sm">
                    @if(!$isPurchased)
                    <a href="{{ route('payment.stripe', ['courseId' => $courseId]) }}"
                       class="flex-1 text-center bg-transparent hover:bg-[#00c2ff]/5 text-[#00C2FF] border border-[#00C2FF] py-2.5 2xl:py-3 rounded-lg">
                        Buy Now
                    </a>
                    @else
                    <a href="{{ route('user.courses.view', ['courseId' => $courseId]) }}"
                       class="flex-1 text-center bg-[#00C2FF] text-white hover:bg-[#0098CC] py-2.5 2xl:py-3 rounded-lg">
                        Open Course
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($isPurchased)
<!-- ⭐ FEEDBACK MODAL -->
<div id="feedback-modal"
     class="fixed inset-0 bg-black/70 backdrop-blur-md hidden flex items-center justify-center z-50">

    <div class="bg-[#0F1627] border border-white/10 rounded-2xl p-6 w-full max-w-md shadow-lg relative">

        <button id="close-feedback-modal"
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">&times;</button>

        <h2 class="text-xl font-semibold text-white mb-4">Leave Your Feedback</h2>

        <!-- Rating Stars -->
        <div id="modal-rating-stars" class="flex gap-2 text-3xl cursor-pointer mb-4 text-gray-500">
            <i data-star="1" class="fa-solid fa-star"></i>
            <i data-star="2" class="fa-solid fa-star"></i>
            <i data-star="3" class="fa-solid fa-star"></i>
            <i data-star="4" class="fa-solid fa-star"></i>
            <i data-star="5" class="fa-solid fa-star"></i>
        </div>

        <textarea id="modal-feedback-comment"
                  rows="3"
                  class="w-full p-3 bg-[#101B2E] border border-white/10 rounded-lg text-gray-200"
                  placeholder="Write your comment (optional)..."></textarea>

        <button id="modal-submit-feedback"
                class="w-full mt-4 bg-[#00C2FF] text-white py-2 rounded-lg">
            Submit Feedback
        </button>
    </div>
</div>
@endif


<script>
    document.addEventListener("DOMContentLoaded", () => {

        const app = document.getElementById("explore-app");
        const courseId = app.getAttribute("data-course-id");

        /* ---------------------------------------------------
        ⭐ JOIN SOCKET ROOM
        ------------------------------------------------------*/
        if (window.socket) {
            window.socket.emit("joinCourse", courseId);

        window.socket.on("feedback:new", (fb) => {
            appendFeedbackToUI(fb);
            loadFeedbackSummary();
        });
    }


    /* ---------------------------------------------------
       LOAD COURSE DETAILS
    ------------------------------------------------------*/
    const titleEl = document.getElementById("course-title");
    const bioEl = document.getElementById("course-bio");
    const trainerNameEl = document.getElementById("trainer-name");
    const trainerExpEl = document.getElementById("trainer-exp");
    const trainerName2El = document.getElementById("trainer-name-2");
    const trainerExp2El = document.getElementById("trainer-exp-2");
    const dateEl = document.getElementById("created-date");
    const priceEl = document.getElementById("course-price");

    fetch(`/user/courses/${courseId}/data`)
        .then(res => res.json())
        .then(resp => {
            const c = resp.data;

            titleEl.textContent = c.title;
            bioEl.textContent = c.description;
            trainerNameEl.textContent = c.trainer?.name ?? "N/A";
            trainerName2El.textContent = c.trainer?.name ?? "N/A";
            trainerExpEl.textContent = (c.trainer?.experience_years ?? 0) + " Years";
            trainerExp2El.textContent = "Experience: " + (c.trainer?.experience_years ?? 0) + " Years";
            priceEl.textContent = c.price;
            dateEl.textContent = new Date(c.created_at).toLocaleDateString();
        });


    /* ---------------------------------------------------
       ⭐ LOAD FEEDBACK LIST FROM BACKEND
    ------------------------------------------------------*/
    const feedbackList = document.getElementById("feedback-list");

    function loadFeedback() {
        fetch(`/user/courses/${courseId}/feedback`)
            .then(res => res.json())
            .then(list => {
                feedbackList.innerHTML = "";
                list.forEach(fb => appendFeedbackToUI(fb));
            });
        }


        /* ---------------------------------------------------
        LOAD COURSE DETAILS
        ------------------------------------------------------*/
        const titleEl = document.getElementById("course-title");
        const bioEl = document.getElementById("course-bio");
        const trainerNameEl = document.getElementById("trainer-name");
        const trainerExpEl = document.getElementById("trainer-exp");
        const trainerName2El = document.getElementById("trainer-name-2");
        const trainerExp2El = document.getElementById("trainer-exp-2");
        const dateEl = document.getElementById("created-date");
        const priceEl = document.getElementById("course-price");

        fetch(`/user/courses/${courseId}`)
            .then(res => res.json())
            .then(resp => {
                const c = resp.data;

                titleEl.textContent = c.title;
                bioEl.textContent = c.description;
                trainerNameEl.textContent = c.trainer?.name ?? "N/A";
                trainerName2El.textContent = c.trainer?.name ?? "N/A";
                trainerExpEl.textContent = (c.trainer?.experience_years ?? 0) + " Years";
                trainerExp2El.textContent = "Experience: " + (c.trainer?.experience_years ?? 0) + " Years";
                priceEl.textContent = c.price;
                dateEl.textContent = new Date(c.created_at).toLocaleDateString();
            });


        /* ---------------------------------------------------
        ⭐ LOAD FEEDBACK LIST FROM BACKEND
        ------------------------------------------------------*/
        // const feedbackList = document.getElementById("feedback-list");

        // function loadFeedback() {
        //     fetch(`/user/courses/${courseId}/feedback`)
        //         .then(res => res.json())
        //         .then(list => {
        //             feedbackList.innerHTML = "";
        //             list.forEach(fb => appendFeedbackToUI(fb));
        //         });
        // }

        // loadFeedback();


        /* ---------------------------------------------------
        ⭐ FUNCTION — APPEND FEEDBACK
        ------------------------------------------------------*/
        function appendFeedbackToUI(fb) {
            const div = document.createElement("div");
            div.className = "bg-white/5 border border-white/10 rounded-xl p-4";

            const userName = fb.user?.name ?? fb.user ?? "Unknown";
            const formattedDate = fb.created_at ? new Date(fb.created_at).toLocaleString() : "";

            div.innerHTML = `
                <div class="flex justify-between items-center">
                    <h4 class="text-white font-medium">${userName}</h4>
                    <p class="text-yellow-400">${"⭐".repeat(fb.rating)}</p>
                </div>
                <p class="text-gray-300 mt-1">${fb.comment ?? ''}</p>
                <p class="text-gray-500 text-xs mt-1">${formattedDate}</p>
            `;

            feedbackList.prepend(div);
        }

        /* ---------------------------------------------------
    ⭐ LOAD FEEDBACK SUMMARY
        ------------------------------------------------------*/
        function loadFeedbackSummary() {
            fetch(`/user/courses/${courseId}/feedback/summary`)
                .then(res => res.json())
                .then(summary => {
                    
                    // Set Average
                    document.getElementById("avg-rating").innerHTML = 
                        `${summary.average} <span class="text-2xl text-yellow-400 align-top">/ 5</span>`;

                    // Average Star Rendering
                    const starContainer = document.getElementById("avg-stars");
                    starContainer.innerHTML = "";
                    const fullStars = Math.floor(summary.average);
                    const halfStar = summary.average % 1 !== 0;

                    for (let i = 1; i <= 5; i++) {
                        if (i <= fullStars) {
                            starContainer.innerHTML += `<i class="fa-solid fa-star"></i>`;
                        } else if (i === fullStars + 1 && halfStar) {
                            starContainer.innerHTML += `<i class="fa-solid fa-star-half-stroke"></i>`;
                        } else {
                            starContainer.innerHTML += `<i class="fa-regular fa-star"></i>`;
                        }
                    }

                    // Review Count
                    document.getElementById("total-reviews").innerHTML = 
                        `Based on ${summary.total} reviews`;

                    // Render Rating Bars
                    const rowsContainer = document.getElementById("rating-rows");
                    const template = document.getElementById("rating-row-template");

                    rowsContainer.innerHTML = ""; // reset

                    for (let star = 5; star >= 1; star--) {
                        const clone = template.content.cloneNode(true);

                        clone.querySelector(".star-label").innerHTML = `${star} Stars`;
                        clone.querySelector(".count").innerHTML = summary.counts[star];

                        const percentage = summary.total ? 
                            (summary.counts[star] / summary.total) * 100 : 0;

                        clone.querySelector(".progress-bar").style.width = `${percentage}%`;

                        rowsContainer.appendChild(clone);
                    }
                });
        }

        loadFeedbackSummary();


        /* ---------------------------------------------------
    ⭐ CHECK IF USER ALREADY SUBMITTED FEEDBACK
        ------------------------------------------------------*/
        function checkUserFeedback() {
            fetch(`/user/courses/${courseId}/feedback/check`)
                .then(res => res.json())
                .then(data => {
                    if (data.hasFeedback) {
                        if (openBtn) {
                            openBtn.classList.add("hidden");   // hide button
                        }
                    }
                });
        }

        checkUserFeedback();




        /* ---------------------------------------------------
        ⭐ FEEDBACK MODAL
        ------------------------------------------------------*/
        const modal = document.getElementById("feedback-modal");
        const openBtn = document.getElementById("open-feedback-modal");
        const closeBtn = document.getElementById("close-feedback-modal");

        let modalRating = 0;

        if (openBtn) openBtn.onclick = () => modal.classList.remove("hidden");
        if (closeBtn) closeBtn.onclick = () => modal.classList.add("hidden");


        /* ---------------------------------------------------
        ⭐ RATING STARS — HOVER + CLICK ANIMATION
        ------------------------------------------------------*/
        const stars = document.querySelectorAll("#modal-rating-stars i");

        stars.forEach(star => {
            star.addEventListener("mouseover", () => {
                const val = star.getAttribute("data-star");
                stars.forEach(s => {
                    s.style.color = (s.getAttribute("data-star") <= val) ? "#FFD700" : "#555";
                });
            });

            star.addEventListener("mouseout", () => {
                stars.forEach(s => {
                    s.style.color = (s.getAttribute("data-star") <= modalRating) ? "#FFD700" : "#555";
                });
            });

            star.addEventListener("click", () => {
                modalRating = star.getAttribute("data-star");
                stars.forEach(s => {
                    s.style.color = (s.getAttribute("data-star") <= modalRating) ? "#FFD700" : "#555";
                });
            });
        });


        /* ---------------------------------------------------
        ⭐ SUBMIT FEEDBACK
        ------------------------------------------------------*/
        document.getElementById("modal-submit-feedback").onclick = () => {

            if (modalRating == 0) {
                alert("Please select a rating.");
                return;
            }

            const comment = document.getElementById("modal-feedback-comment").value;

            fetch(`/user/courses/feedback/store`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    course_id: courseId,
                    rating: modalRating,
                    comment: comment
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.status) {
                if (data.message?.includes("duplicate")) {
                    alert("You have already submitted feedback for this course.");
                    modal.classList.add("hidden");
                    return;
                }
                alert("Error submitting feedback.");
                return;
            }


                modal.classList.add("hidden");
                document.getElementById("modal-feedback-comment").value = "";

                const newFb = {
                    user: "{{ auth()->user()->name }}",
                    rating: modalRating,
                    comment: comment,
                    created_at: new Date().toISOString()
                };

                feedbackList.innerHTML = "";  // clear old list
                loadFeedback();               // reload from DB
                loadFeedbackSummary();        // refresh summary


                // Real-time socket push
                window.socket.emit("feedback:created", {
                    courseId,
                    ...newFb
                });
            });
        };

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const skeleton = document.getElementById("course-skeleton");
        const realContent = document.getElementById("explore-app");

        // Fake delay (2 seconds)
        setTimeout(() => {
            skeleton.style.display = "none";   // hide skeleton
            realContent.classList.remove("hidden"); 
            realContent.classList.add("flex"); 
        }, 2000);  // <-- change this to 3000 or 1500 depending on your liking
    });
</script>

<script>
    function toggleAccordion(btn) {
        let content = btn.nextElementSibling;
        let icon = btn.querySelector("i");

        // Close others
        document.querySelectorAll(".accordion-content").forEach(el => {
            if (el !== content) el.style.maxHeight = null;
        });

        document.querySelectorAll(".accordion-content + button i").forEach(el => {
            if (el !== icon) el?.classList?.remove("rotate-180");
        });

         // Toggle selected accordion
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }

        icon.classList.toggle("rotate-180");
    }
</script>

@endsection
