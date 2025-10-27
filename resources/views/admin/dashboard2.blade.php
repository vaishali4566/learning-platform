<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Dashboard | E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

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

        #sidebar.sidebar-collapsed .menu-item:hover .tooltip {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(-50%) translateX(8px);
        }

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
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <div class="flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
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
                    <i data-lucide="book-open" class="w-6 h-6 text-blue-600"></i>
                    <span id="sidebar-title" class="text-lg font-semibold text-gray-700 whitespace-nowrap">E-Learning Admin</span>
                </div>
            </div>

            <nav class="flex-1 mt-4 relative">
                <!-- Menu Items -->
                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 text-blue-600"></i>
                    <span class="sidebar-text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <i data-lucide="library" class="w-5 h-5 text-green-600"></i>
                    <span class="sidebar-text">Courses</span>
                    <span class="tooltip">Courses</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <i data-lucide="users" class="w-5 h-5 text-yellow-600"></i>
                    <span class="sidebar-text">Students</span>
                    <span class="tooltip">Students</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <i data-lucide="user-check" class="w-5 h-5 text-red-600"></i>
                    <span class="sidebar-text">Instructors</span>
                    <span class="tooltip">Instructors</span>
                </div>

                <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600"></i>
                    <span class="sidebar-text">Reports</span>
                    <span class="tooltip">Reports</span>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main id="mainContent" class="flex-1 ml-64 p-6 transition-all duration-300">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to E-Learning Dashboard</h1>
                <p class="text-gray-600 mb-4">Manage your courses, instructors, and students efficiently from one place.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-blue-700 font-semibold flex items-center gap-2"><i data-lucide="book-open-text"></i> Courses</h2>
                        <p class="text-gray-600 text-sm">Manage and view all available courses.</p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-green-700 font-semibold flex items-center gap-2"><i data-lucide="users"></i> Students</h2>
                        <p class="text-gray-600 text-sm">View enrolled students and progress.</p>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg shadow-sm">
                        <h2 class="text-yellow-700 font-semibold flex items-center gap-2"><i data-lucide="chart-line"></i> Reports</h2>
                        <p class="text-gray-600 text-sm">Track performance and analytics.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons(); // Initialize Lucide icons

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