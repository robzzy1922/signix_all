<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Signix</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#2A3042] text-white">
            <div class="p-4">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo_signix.png') }}" alt="Logo" class="h-8">
                    <span class="text-lg font-semibold">Admin Panel</span>
                </div>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.adminDashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.adminDashboard') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dosen.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Dosen
                </a>
                <a href="{{ route('admin.ormawa.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.ormawa.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Ormawa
                </a>

                <a href="{{ route('admin.dokumen.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dokumen.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Dokumen
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            @include('layouts.admin.partials.navbar')

            <!-- Page Content -->
            <main class="p-6">
                <div>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
