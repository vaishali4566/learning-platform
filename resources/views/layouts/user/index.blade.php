<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard | E-Learning')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <script>
        // Run before render
        document.documentElement.classList.add(
            "sidebar-init-loading",
        );
    </script>


    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ✅ Material Icons for chat ticks -->

    <style>
        /* ===== TRANSITIONS ===== */
        .transition-all {
            transition: all 0.28s ease-in-out;
        }

        /* ===== SIDEBAR WIDTHS ===== */
        .sidebar-collapsed {
            width: 5rem;
        }

        .sidebar-expanded {
            width: 14rem;
        }

        /* PAGE FADE-IN ANIMATION */
        .page-hidden {
            opacity: 0;
        }

        .page-visible {
            opacity: 1;
            transition: opacity 0.35s ease;
        }

        /* SIDEBAR INITIAL HIDDEN STATE */
        .sidebar-init-loading #sidebar {
            opacity: 0;
            transform: translateX(-20px); 
        }

        /* SIDEBAR FADE + SLIDE IN */
        #sidebar.sidebar-animate-in {
            opacity: 1 !important;
            transform: translateX(0);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        /* MOBILE SLIDE-IN */
        @media (max-width: 1023px) {
            .sidebar-init-loading #sidebar {
                transform: translateX(-40px); 
            }
        }

        /* ===== TOOLTIP ===== */
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

        /* ===== BACKGROUND COLORS ===== */
        body {
            background-color: #0f172a;
        }

        #mainContent {
            background-color: #0f172a;
            color: #fff;
            overflow-y: auto;
            height: calc(100vh - 2rem);
        }

        /* #sidebar {
            background-color: #1e293b;
            color: white;
        } */

        /* ===== NOTIFICATION CONTAINER ===== */
        #notification-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 9999;
        }

        .notification-card {
            background-color: rgba(30, 41, 59, 0.95);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            max-width: 300px;
            cursor: pointer;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.4s ease;
        }

        .notification-card.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification-card .sender {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .notification-card .message {
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="bg-[#F5F7FA] page-hidden text-[#0F172A] dark:bg-[#0f172a] dark:text-white flex flex-col h-screen overflow-hidden">

    {{-- Define current user for chat notifications --}}
    <script>
        window.currentUser = {
            id: "{{ auth()->user()->id }}",
            name: "{{ auth()->user()->name }}",
            type: "user"
        };
        window.currentRoomId = null; 
    </script>   

    {{-- BODY LAYOUT --}}
    <div class="flex flex-1 transition-all duration-300 overflow-hidden">
        {{-- SIDEBAR --}}
        @include('partials.user.sidebar')

        <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden lg:hidden"></div>

        <div id="mainContent" class="flex-1 ml-20 lg:ml-0 transition-all duration-300 flex flex-col">            
            {{-- NAVBAR --}}
            <div id="navbar" class=" fixed top-0 left-20 lg:left-[14rem] right-0 bg-[#F0F4FA] dark:bg-gray-900 border-b border-[#D5DEE8] dark:border-gray-800">
                @include('partials.user.navbar')
            </div>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 pt-16 transition-all duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- CHATBOT --}}
    @include('partials.chatbot')

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const html = document.documentElement;
            const body = document.body;
            const sidebar = document.getElementById("sidebar");

            requestAnimationFrame(() => {
                // Remove initial width-lock class
                html.classList.remove("sidebar-init-loading");

                // Trigger sidebar animation
                sidebar.classList.add("sidebar-animate-in");

                // Reveal page fade-in
                requestAnimationFrame(() => {
                    body.classList.remove("page-hidden");
                    body.classList.add("page-visible");
                });
            });
        });
    </script>

    {{-- LUCIDE ICONS & SIDEBAR SCRIPT --}}
    <script>
        lucide.createIcons();

        const toggleBtn = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("mainContent");
        const navbar = document.getElementById("navbar");
        const overlay = document.getElementById("sidebarOverlay");
        const textElements = document.querySelectorAll(".sidebar-text");
        const chevronIcon = document.getElementById('chevronIcon');
        const menuIcon = document.getElementById('menuIcon');

        const isDesktop = () => window.innerWidth >= 1024;

        function collapseSidebar() {
            sidebar.classList.add("sidebar-collapsed");
            sidebar.classList.remove("sidebar-expanded");

            if (!isDesktop()) {
                overlay.classList.add("hidden");
            } 
            navbar.style.left = "5rem";
            navbar.style.width = "calc(100% - 5rem)";
            textElements.forEach(el => el.classList.add("hidden"));
            chevronIcon.classList.add("hidden");
            menuIcon.classList.remove("hidden");
        }

        function expandSidebar() {
            sidebar.classList.add("sidebar-expanded");
            sidebar.classList.remove("sidebar-collapsed");

            if (isDesktop()) {
                // Desktop behaviour
                navbar.style.left = "14rem";
                navbar.style.width = "calc(100% - 14rem)";
            } else {
                // Mobile: show sidebar as overlay drawer
                sidebar.classList.remove("hidden");
                overlay.classList.remove("hidden");
            }

            textElements.forEach(el => el.classList.remove("hidden"));
            chevronIcon.classList.remove("hidden");
            menuIcon.classList.add("hidden");
        }

        toggleBtn.addEventListener("click", () => {
            if (sidebar.classList.contains("sidebar-expanded")) {
                collapseSidebar();
            } else {
                expandSidebar();
            }
        });

        overlay.addEventListener("click", () => {
            collapseSidebar();
        });

        document.addEventListener("DOMContentLoaded", () => {
            requestAnimationFrame(() => {
                    document.documentElement.classList.remove("sidebar-init-loading");
                });
            
                if (!isDesktop()) {
                    collapseSidebar(); // collapse for mobile only
                }
        });

        // On resize, reset layout
        window.addEventListener("resize", () => {
            if (isDesktop()) {
                expandSidebar();
            } else {
                collapseSidebar();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const html = document.documentElement;
            const themeToggle = document.getElementById("themeToggle");
            const sunIcon = document.getElementById("sunIcon");
            const moonIcon = document.getElementById("moonIcon");

            // Load saved theme
            if (localStorage.theme === "dark") {
                html.classList.add("dark");
                sunIcon.classList.remove("hidden");
                moonIcon.classList.add("hidden");
            } else {
                html.classList.remove("dark");
                sunIcon.classList.add("hidden");
                moonIcon.classList.remove("hidden");
            }

            themeToggle.addEventListener("click", () => {
                const isDark = html.classList.toggle("dark");

                // Switch icons
                sunIcon.classList.toggle("hidden", !isDark);
                moonIcon.classList.toggle("hidden", isDark);

                // Save preference
                localStorage.theme = isDark ? "dark" : "light";
            });
        });
    </script>

    {{-- NOTIFICATION CONTAINER --}}
    <div id="notification-container"></div>

    {{-- CHAT SOCKET & NOTIFICATIONS --}}
    @vite('resources/js/chat.js')

</body>
</html>
