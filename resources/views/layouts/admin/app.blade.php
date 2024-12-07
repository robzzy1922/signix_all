<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Signix</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar-text {
            display: block; /* Pastikan teks ditampilkan secara default */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 shadow-lg transition-all duration-300 transform">
            <div class="flex justify-between items-center p-4 border-b border-gray-700">
                <div class="flex items-center space-x-2">
                    <img class="w-8 h-8 logo_signix" src="{{ asset('images/logo_signix.png') }}" alt="Logo">
                    <span class="text-xl font-semibold text-white sidebar-text">Admin Panel</span>
                </div>
                <button id="toggleSidebar" class="p-2 text-gray-300 rounded-lg hover:bg-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <nav class="p-4">
                <a href="{{ route('admin.adminDashboard') }}" class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white group">
                    <div class="min-w-[20px] flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                    <span class="ml-3 sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-3 mt-2 {{ request()->routeIs('admin.dosen.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-lg group">
                    <div class="min-w-[20px] flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="ml-3 sidebar-text">Dosen</span>
                </a>

                <a href="{{ route('admin.ormawa.index') }}" class="flex items-center px-4 py-3 mt-2 {{ request()->routeIs('admin.ormawa.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-lg group">
                    <div class="min-w-[20px] flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="ml-3 sidebar-text">Ormawa</span>
                </a>

                <a href="{{ route('admin.dokumen.index') }}" class="flex items-center px-4 py-3 mt-2 {{ request()->routeIs('admin.dokumen.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-lg group">
                    <div class="min-w-[20px] flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="ml-3 sidebar-text">Dokumen</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 transition-all duration-300" id="mainContent">
            @include('layouts.admin.partials.navbar')

            <!-- Page Content -->
            <main class="p-6">
                <div class="overflow-x-auto">
                    @yield('content')
                </div>
            </main>
        </main>
    </div>

    <!-- Add this script before closing body tag -->
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleButton = document.getElementById('toggleSidebar');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const logoSignix = document.querySelectorAll('.logo_signix');
            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');
                toggleButton.classList.remove('left-64');
                toggleButton.classList.add('left-20');
                logoSignix.forEach(text => text.style.display = 'none');
                sidebarTexts.forEach(text => text.style.display = 'none');
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
                toggleButton.classList.remove('left-20');
                toggleButton.classList.add('left-64');
                logoSignix.forEach(text => text.style.display = '');
                sidebarTexts.forEach(text => text.style.display = '');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
