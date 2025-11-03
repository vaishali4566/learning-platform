<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'User Dashboard | E-Learning')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

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
    </style>
</head>

<body class="bg-gray-900 flex flex-col h-screen overflow-hidden">

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
                // collapse
                mainContent.classList.replace('ml-64', 'ml-20');
                sidebarTextElements.forEach(el => el.classList.add('hidden'));
                sidebarTitle?.classList.add('hidden');
            } else {
                // expand
                mainContent.classList.replace('ml-20', 'ml-64');
                sidebarTextElements.forEach(el => el.classList.remove('hidden'));
                sidebarTitle?.classList.remove('hidden');
            }
        });
    </script>
@vite(['resources/js/chat.js'])
</body>

</html>
