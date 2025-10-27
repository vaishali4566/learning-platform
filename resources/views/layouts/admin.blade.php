<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard | E-Learning')</title>
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

        /* Tooltip */
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

    {{-- NAVBAR --}}
    @include('partials.admin.navbar')

    {{-- LAYOUT BODY --}}
    <div class="flex flex-1 pt-16 overflow-hidden">
        {{-- SIDEBAR --}}
        @include('partials.admin.sidebar')

        {{-- MAIN CONTENT --}}
        <main id="mainContent" class="flex-1 bg-black ml-64 p-6 transition-all duration-300">
            @yield('content')
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
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>