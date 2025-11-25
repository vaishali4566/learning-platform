<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning | Learn Anything</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">



    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
        font-family: 'Poppins', sans-serif;
        }
        .handlee {
            font-family: 'Shantell Sans', cursive;
        }
            /* Slim Modern Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 10px; }

            /* Custom keyframes for infinite scrolling */
            @keyframes scroll-left {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            @keyframes scroll-right {
                0% { transform: translateX(-50%); }
                100% { transform: translateX(0); }
            }

            .animate-scroll-left {
                animation: scroll-left 22s linear infinite;
            }
            .animate-scroll-right {
                animation: scroll-right 22s linear infinite;
            }
            /* Pause animation on hover */
            .scroll-track:hover {
                animation-play-state: paused !important;
            }

            /* Make hovered testimonial card expand */
            .testimonial:hover {
                white-space: normal !important;
                width: 22rem !important; /* expand */
                height: auto !important;
            }

            /* Prevent clipping inside the card */
            .testimonial p {
                overflow: visible !important;
            }
            /* make track a long horizontal flex and animate translateX */
        .mentors-track {
        animation: scroll-left 28s linear infinite;
        }

        .mentors-track:hover {
        animation-play-state: paused;
        }

        @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); } /* because content is duplicated */
        }

        /* responsive: on small screens allow manual horizontal scroll (no auto animation) */
        @media (max-width: 768px) {
        .mentors-track {
            animation: none;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        }
        a.handlee {
        display: inline-flex;
        align-items: center;
        }
        a.handlee span {
        transition: transform 0.3s;
        }
        a.handlee:hover span {
        transform: rotate(-35deg);
        }
    </style>
</head>

<body class="bg-gray-900 text-white relative overflow-y-scroll">

    <!-- Modern Orb + Grid Background -->
<div class="absolute inset-0 -z-10"
     style="
        background: #020617;
        background-image:
            linear-gradient(to right, rgba(71,85,105,0.15) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(71,85,105,0.15) 1px, transparent 1px),
            radial-gradient(circle at 50% 60%, rgba(236,72,153,0.15) 0%, rgba(168,85,247,0.05) 40%, transparent 70%);
        background-size: 40px 40px, 40px 40px, 100% 100%;
     ">
</div>


    <!-- Navbar (Fixed) -->
    <nav class="fixed top-0 left-0 right-0 bg-gray-900 z-50">
        <div class="max-w-7xl mx-auto py-5 px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-wide">
                Globus<span class="text-indigo-500">Learn</span>
            </h1>

            <div class="space-x-6 hidden md:block">
                <a href="#" class="text-gray-300 hover:text-white transition">Home</a>
                <a href="#" class="text-gray-300 hover:text-white transition">Courses</a>
                <a href="#" class="text-gray-300 hover:text-white transition">Features</a>
                <a href="#" class="text-gray-300 hover:text-white transition">Testimonials</a>
                <a href="#" class="text-gray-300 hover:text-white transition">About</a>
            </div>

            <div class="space-x-3">
                <a href="#" class="px-4 py-2 rounded-lg border border-gray-500 hover:bg-white hover:text-gray-900 transition">Login</a>
                <a href="#" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Wrapper -->
 

        <!-- HERO SECTION -->
        <section class="relative min-h-screen flex items-center overflow-hidden text-white">

            

            <div class="grid lg:grid-cols-2 gap-10 w-full max-w-7xl mx-auto">

                <!-- LEFT CONTENT -->
                <div class="flex flex-col justify-center">

                    <h2 class="handlee text-5xl md:text-5xl font-bold leading-tight">
                        Learn From Top Mentors &  
                        <span class="text-indigo-400 relative">
                            Build Real Skills
                            <!-- Underline SVG -->
                            <svg class="absolute -bottom-15 left-17 w-60" viewBox="20 0 100 20">
                                <path d="M5 15 C 30 5, 70 5, 95 15"
                                    stroke="#6366F1"
                                    stroke-width="4"
                                    fill="transparent"
                                    stroke-linecap="round"/>
                            </svg>
                            <svg class="absolute -bottom-15 left-15 w-60" viewBox="0 0 100 20">
                                <path d="M5 15 C 30 5, 70 5, 95 15"
                                    stroke="#6366F1"
                                    stroke-width="4"
                                    fill="transparent"
                                    stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h2>

                    <p class="handlee text-gray-300 text-lg mt-16 max-w-md">
                        Your one-stop destination to master job-oriented skills with structured learning paths, expert mentorship & practical projects.
                    </p>

                    <!-- Bullet Features -->
                    <div class="handlee mt-8 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center bg-indigo-600/30 rounded-full">
                                📘
                            </span>
                            <p class="text-gray-300">Curated Skill Roadmaps</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center bg-indigo-600/30 rounded-full">
                                👨‍🏫
                            </span>
                            <p class="text-gray-300">1:1 Mentorship by Experts</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center bg-indigo-600/30 rounded-full">
                                🛠️
                            </span>
                            <p class="text-gray-300">Real Projects & Assessments</p>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="flex gap-10 mt-10">
                        <div>
                            <h3 class="text-4xl font-bold text-indigo-400">270k+</h3>
                            <p class="text-gray-400 text-sm">Learners</p>
                        </div>

                        <div>
                            <h3 class="text-4xl font-bold text-indigo-400">10L+</h3>
                            <p class="text-gray-400 text-sm">Community Reach</p>
                        </div>

                        <div>
                            <h3 class="text-4xl font-bold text-indigo-400">4.9/5</h3>
                            <p class="text-gray-400 text-sm">Average Rating</p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a href="#" class="handlee inline-flex items-center px-6 py-3 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 transition">
                            Join Now
                            <span class="ml-2 transform transition-transform duration-300 group-hover:rotate-45 inline-block">➔</span>
                        </a>
                    </div>
                </div>

                <!-- RIGHT FLOATING CARDS -->
                <div class="relative flex justify-center items-center">

                    <div class="grid grid-cols-2 gap-4">

    <!-- Row 1 Col 1 -->
    <div class="bg-[#e9fcf1] p-6 text-black rounded-2xl border border-gray-700 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">📚</div>
        <h4 class="font-semibold text-lg">Digital Products</h4>
        <p class="text-black text-sm mt-2">Premium skill-based resources.</p>
    </div>

    <!-- Row 1 & 2 Col 2 (Tall Card) -->
    <div class="bg-[#fff5e5] text-black p-6 row-span-2 rounded-2xl border border-gray-700 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">🧭</div>
        <h4 class="font-semibold text-lg">Mentors</h4>
        <p class="text-black text-sm mt-2">300+ Mentors, 5000+ Queries Resolved</p>
    </div>

    <!-- Row 2 Col 1 -->
    <div class="bg-[#ffccf7] text-black p-6 rounded-2xl border border-gray-700 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">👨‍🏫</div>
        <h4 class="font-semibold text-lg">Mentorship</h4>
        <p class="text-black text-sm mt-2">Personal guidance from experts.</p>
    </div>

    <!-- Row 3 & 4 Col 1 (Tall Card) -->
    <div class="bg-[#ffe5e6] text-black p-6 rounded-2xl border border-gray-700 row-span-2 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">🚀</div>
        <h4 class="font-semibold text-lg">Roadmaps</h4>
        <p class="text-black text-sm mt-2">Solve skill based roadmaps</p>
    </div>

    <!-- Row 3 Col 2 -->
    <div class="bg-[#e5edff] text-black p-6 rounded-2xl border border-gray-700 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">🚀</div>
        <h4 class="font-semibold text-lg">Bootcamps</h4>
        <p class="text-black text-sm mt-2">Hands-on cohort-based training.</p>
    </div>

    <!-- Row 4 Col 2 -->
    <div class="bg-[#94a3b8] text-black p-6 rounded-2xl border border-gray-700 shadow-lg hover:scale-105 transition">
        <div class="text-4xl mb-3">🚀</div>
        <h4 class="font-semibold text-lg">Mock Interview</h4>
        <p class="text-black text-sm mt-2">Practice & Ace your next interview.</p>
    </div>

</div>


                </div>
            </div>
        </section>


       <!-- Infinite Scrolling Mentors Section -->
<section class="py-16">
  <div>
    <h2 class="handlee text-3xl text-center md:text-4xl font-bold mb-4">Meet Our <span class="text-indigo-400">Inspirational Mentors</span></h2>
    <p class="text-gray-300 handlee text-md text-center mb-8 ">
      Discover the Mentors who are ready to guide, inspire, and empower you. Find the perfect mentor to reach your goals.
    </p>

    <!-- marquee container -->
    <div class="relative overflow-hidden">
      <!-- fade edges -->
      

      <!-- scrolling track (duplicated content inside for infinite loop) -->
      <div class="mentors-track will-change-transform flex gap-6 py-4">
        <!-- start: repeated block set A -->
        <!-- Repeat as many cards as you want; these will be duplicated below for seamless loop -->
        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Nirbhay" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Nirbhay Luthra</h3>
            <div class="text-sm text-indigo-400 my-1">Software Development Engineer 2 @ Expedia Group</div>
            <div class="text-xs text-gray-400">5+ Years Expedia Group | pokerbaazi | citymall</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Aman" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Aman Singh Ratnam</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Product Manager @ THB (India)</div>
            <div class="text-xs text-gray-400">6+ Years THB (India) | HomeLane | Droom</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Manish" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Manish Kumar</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Member Technical @ Salesforce</div>
            <div class="text-xs text-gray-400">12+ Years Salesforce | Oracle | PEGA</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Ribhav" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Ribhav</h3>
            <div class="text-sm text-indigo-400 my-1">Course Mentor @ Geeksforgeeks</div>
            <div class="text-xs text-gray-400">9+ Years Geeksforgeeks | Coding ninjas</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Arpan" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Arpan Banerjee</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Member of Technical Staff @ Salesforce</div>
            <div class="text-xs text-gray-400">7+ Years Salesforce | Walmart | Oracle</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <!-- end set A -->

        <!-- Duplicate set A for seamless infinite effect -->
        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Nirbhay2" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Nirbhay Luthra</h3>
            <div class="text-sm text-indigo-400 my-1">Software Development Engineer 2 @ Expedia Group</div>
            <div class="text-xs text-gray-400">5+ Years Expedia Group | pokerbaazi | citymall</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Aman2" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Aman Singh Ratnam</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Product Manager @ THB (India)</div>
            <div class="text-xs text-gray-400">6+ Years THB (India) | HomeLane | Droom</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Manish2" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Manish Kumar</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Member Technical @ Salesforce</div>
            <div class="text-xs text-gray-400">12+ Years Salesforce | Oracle | PEGA</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Ribhav2" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Ribhav</h3>
            <div class="text-sm text-indigo-400 my-1">Course Mentor @ Geeksforgeeks</div>
            <div class="text-xs text-gray-400">9+ Years Geeksforgeeks | Coding ninjas</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

        <div class="min-w-[280px] bg-gray-800/60 p-4 rounded-2xl border border-gray-700 backdrop-blur-xl shadow-md flex gap-4 items-start">
          <img src="/mnt/data/ed5f0f1b-df1f-437c-9b56-4c294c3f4c1e.png" alt="Arpan2" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
          <div>
            <h3 class="font-semibold text-white">Arpan Banerjee</h3>
            <div class="text-sm text-indigo-400 my-1">Senior Member of Technical Staff @ Salesforce</div>
            <div class="text-xs text-gray-400">7+ Years Salesforce | Walmart | Oracle</div>
            <a href="#" class="inline-block mt-3 text-sm text-indigo-300 hover:underline">View Profile</a>
          </div>
        </div>

      </div> <!-- .mentors-track -->
    </div> <!-- relative -->

    <!-- small call to action -->
    <div class="mt-8 text-center">
        <a href="#" class="handlee inline-flex items-center px-6 py-3 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 transition">
            Explore All Mentors
            <span class="ml-2 transform transition-transform duration-300 group-hover:rotate-45 inline-block">➔</span>
        </a>
    </div>
  </div>
  </section>

        <!-- Popular Courses -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="text-4xl font-bold mb-10">Popular Courses</h2>

            <div class="grid md:grid-cols-3 gap-8">
                @for($i=1;$i<=3;$i++)
                <div class="bg-gray-800/60 p-6 rounded-xl border border-gray-700 hover:border-indigo-500 transition">
                    <div class="h-40 bg-gray-700 rounded-lg mb-4"></div>
                    <h3 class="text-xl font-semibold mb-2">Course Title {{ $i }}</h3>
                    <p class="text-gray-300 mb-4">Short description about the course goes here.</p>
                    <a href="#" class="text-indigo-400 hover:text-indigo-300">Learn More →</a>
                </div>
                @endfor
            </div>
        </section>

        <!-- Features -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="text-4xl font-bold mb-10">Why Choose GlobusLearn?</h2>

            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-gray-800/50 p-8 rounded-xl border border-gray-700">
                    <h3 class="text-xl font-bold mb-3">Interactive Lessons</h3>
                    <p class="text-gray-300">Hands-on exercises, quizzes, and real-world tasks.</p>
                </div>
                <div class="bg-gray-800/50 p-8 rounded-xl border border-gray-700">
                    <h3 class="text-xl font-bold mb-3">Expert Mentors</h3>
                    <p class="text-gray-300">Learn directly from industry professionals.</p>
                </div>
                <div class="bg-gray-800/50 p-8 rounded-xl border border-gray-700">
                    <h3 class="text-xl font-bold mb-3">Career Oriented</h3>
                    <p class="text-gray-300">Structured roadmaps to prepare you for jobs.</p>
                </div>
            </div>
        </section>

        <!-- ⭐ NEW: Animated Infinite Testimonials ⭐ -->
        <section class="py-20 ">
            <h2 class="text-4xl text-center font-bold mb-10">What Our Students Say</h2>

            <div class="relative overflow-hidden">


                <!-- Lane 1 (Left to Right) -->
                <div class="scroll-track flex gap-6 whitespace-nowrap animate-scroll-left">

                    @for($i=1;$i<=6;$i++)
                    <div class="bg-gray-800/60 p-6 w-80 rounded-xl border border-gray-700 shadow-lg backdrop-blur-xl inline-block">
                        <div class="flex items-center gap-4 mb-3">
                            <img src="https://i.pravatar.cc/60?img={{ $i }}" class="w-12 h-12 rounded-full">
                            <div>
                                <h4 class="font-semibold text-indigo-400">Student {{ $i }}</h4>
                                <p class="text-sm text-gray-400">Learner</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            🎓 “This platform helped me crack my first internship! The learning path is amazing.”
                        </p>
                    </div>
                    @endfor
                </div>

                <!-- Lane 2 (Right to Left) -->
                <div class="scroll-track flex gap-6 whitespace-nowrap animate-scroll-right mt-8">

                    @for($i=7;$i<=12;$i++)
                    <div class="bg-gray-800/60 p-6 w-80 rounded-xl border border-gray-700 shadow-lg backdrop-blur-xl inline-block">
                        <div class="flex items-center gap-4 mb-3">
                            <img src="https://i.pravatar.cc/60?img={{ $i }}" class="w-12 h-12 rounded-full">
                            <div>
                                <h4 class="font-semibold text-indigo-400">Student {{ $i }}</h4>
                                <p class="text-sm text-gray-400">Learner</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            ⭐ “Best decision ever! The mentors are really helpful and experienced.”
                        </p>
                    </div>
                    @endfor
                </div>

            </div>
        </section>

        <!-- CTA Section -->
        <section class="text-center py-20">
            <h2 class="text-4xl font-bold mb-4">Start Your Learning Journey Today</h2>
            <p class="text-gray-300 mb-8">Join thousands of learners upgrading their careers.</p>
            <div class="mt-8 text-center">
                <a href="#" class="handlee inline-flex items-center px-6 py-3 bg-indigo-600 rounded-lg text-white font-medium hover:bg-indigo-700 transition">
                    Join Now
                    <span class="ml-2 transform transition-transform duration-300 group-hover:rotate-45 inline-block">➔</span>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-700 py-8 mt-10">
            <p class="text-center text-gray-400">© 2025 GlobusLearn. All Rights Reserved.</p>
        </footer>

    

</body>
</html>
