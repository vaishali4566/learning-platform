<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Dashboard | E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .transition-all {
            transition: all 0.28s ease-in-out;
        }

        .sidebar-collapsed {
            width: 5rem;
        }

        .sidebar-expanded {
            width: 16rem;
        }

        /* Tooltip base - invisible by default */
        .tooltip {
            position: absolute;
            left: calc(100% + 0.5rem);
            top: 50%;
            transform: translateY(-50%) translateX(0);
            background-color: rgba(17, 24, 39, 0.98);
            /* slate-900 */
            color: #fff;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transform-origin: left center;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.3);
            transition: opacity .18s ease, transform .18s ease;
            z-index: 40;
        }

        /* Show tooltip only when the sidebar is collapsed and menu-item is hovered */
        #sidebar.sidebar-collapsed .menu-item:hover .tooltip {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(-50%) translateX(8px);
        }

        /* Small caret/arrow */
        .tooltip::after {
            content: "";
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent rgba(17, 24, 39, 0.98) transparent transparent;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <!-- NAVBAR -->
    <nav class="bg-blue-600 text-white px-6 py-3 flex justify-between items-center shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center gap-3">
            <button id="sidebarToggle" class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Logo in navbar -->
            <div class="flex items-center gap-2">
                <img src="https://img.icons8.com/color/48/000000/graduation-cap--v2.png" alt="Logo" class="w-8 h-8">
                <span class="font-semibold text-lg">E-Learning</span>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-gray-200">Profile</a>
            <a href="#" class="hover:text-gray-200">Courses</a>
            <a href="#" class="hover:text-gray-200">All Courses</a>
        </div>
    </nav>

    <!-- LAYOUT -->
    <div class="flex flex-1 pt-16 overflow-hidden">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="bg-white shadow-md sidebar-expanded transition-all fixed top-16 left-0 bottom-0 flex flex-col z-30">
            <div class="flex items-center justify-center py-5 border-b">
                <div class="flex items-center gap-2">
                    <img src="https://img.icons8.com/color/48/000000/classroom.png" alt="Admin Logo" class="w-8 h-8 sidebar-logo">
                    <span id="sidebar-title" class="text-lg font-semibold text-gray-700 whitespace-nowrap">E-Learning Admin</span>
                </div>
            </div>

            <nav class="flex-1 mt-4 relative">
                <!-- Menu item template -->
                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4 0v-8m4 4l2 2m-2-2l-7-7-7 7" />
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-8-4h8m-2 12h2a2 2 0 002-2V6a2 2 0 00-2-2h-2" />
                    </svg>
                    <span class="sidebar-text">Courses</span>
                    <span class="tooltip">Courses</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01.88 7.903A4 4 0 0016 7zM6 7a4 4 0 00-.88 7.903A4 4 0 016 7z" />
                    </svg>
                    <span class="sidebar-text">Students</span>
                    <span class="tooltip">Students</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c.77 0 1.47.3 2 .78l.64-.64a2 2 0 112.83 2.83l-.64.64c.48.53.78 1.23.78 2a3 3 0 01-3 3h-4a3 3 0 01-3-3c0-.77.3-1.47.78-2l-.64-.64a2 2 0 112.83-2.83l.64.64A2.98 2.98 0 0112 8z" />
                    </svg>
                    <span class="sidebar-text">Instructors</span>
                    <span class="tooltip">Instructors</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 4H7m4-8H7m4 12h2a2 2 0 002-2V6a2 2 0 00-2-2h-2" />
                    </svg>
                    <span class="sidebar-text">Reports</span>
                    <span class="tooltip">Reports</span>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main id="mainContent" class="flex-1 bg-black ml-64 p-6 transition-all duration-300">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to E-Learning Dashboard</h1>
                <p class="text-gray-600 mb-4">Manage your courses, instructors, and students efficiently from one place.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-blue-700 font-semibold">Courses</h2>
                        <p class="text-gray-600 text-sm">Manage and view all available courses.</p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-green-700 font-semibold">Students</h2>
                        <p class="text-gray-600 text-sm">View enrolled students and progress.</p>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-yellow-700 font-semibold">Reports</h2>
                        <p class="text-gray-600 text-sm">Track performance and analytics.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarTextElements = document.querySelectorAll('.sidebar-text');
        const sidebarTitle = document.getElementById('sidebar-title');

        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded');

            if (isCollapsed) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');
                sidebarTextElements.forEach(el => el.classList.add('hidden'));
                sidebarTitle.classList.add('hidden');
            } else {
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
                sidebarTextElements.forEach(el => el.classList.remove('hidden'));
                sidebarTitle.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>