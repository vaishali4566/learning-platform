<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard | E-Learning')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            width: 16rem;
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
            height: calc(100vh - 4rem);
        }

        #sidebar {
            background-color: #1e293b;
            color: white;
        }

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

<body class="bg-gray-900 flex flex-col h-screen overflow-hidden">

    {{-- Define current user for chat notifications --}}
    <script>
        window.currentUser = {
            id: "{{ auth()->user()->id }}",
            name: "{{ auth()->user()->name }}",
            type: "user"
        };
        window.currentRoomId = null; 
    </script>

    {{-- NAVBAR --}}
    <div class="fixed top-0 left-0 right-0 z-50 bg-gray-900 border-b border-gray-800">
        @include('partials.user.navbar')
    </div>

    {{-- BODY LAYOUT --}}
    <div class="flex flex-1 pt-16 overflow-hidden">
        {{-- SIDEBAR --}}
        @include('partials.user.sidebar')

        {{-- MAIN CONTENT --}}
        <main id="mainContent" class="flex-1 ml-64 transition-all duration-300">
            @yield('content')
        </main>
    </div>

    {{-- LUCIDE ICONS & SIDEBAR SCRIPT --}}
    <script>
        lucide.createIcons();

        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarTextElements = document.querySelectorAll('.sidebar-text');
        const sidebarTitle = document.getElementById('sidebar-title');

        toggleBtn.addEventListener('click', () => {
            const isNowCollapsed = sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded', !isNowCollapsed);

            if (isNowCollapsed) {
                mainContent.classList.replace('ml-64', 'ml-20');
                sidebarTextElements.forEach(el => el.classList.add('hidden'));
                sidebarTitle?.classList.add('hidden');
            } else {
                mainContent.classList.replace('ml-20', 'ml-64');
                sidebarTextElements.forEach(el => el.classList.remove('hidden'));
                sidebarTitle?.classList.remove('hidden');
            }
        });
    </script>

    {{-- NOTIFICATION CONTAINER --}}
    <div id="notification-container"></div>

    {{-- CHAT SOCKET & NOTIFICATIONS --}}
    @vite('resources/js/chat.js')

</body>
</html>
