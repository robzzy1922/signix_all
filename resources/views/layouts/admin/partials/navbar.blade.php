<header class="relative z-50 bg-white shadow">
    <div class="flex justify-between items-center px-6 py-4">
        <h1 class="text-2xl font-semibold animate-fadeIn">@yield('title', 'Dashboard')</h1>

        <div class="flex items-center space-x-4">
            <!-- Admin Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center p-2 space-x-2 rounded-full transition-colors duration-200 hover:bg-gray-100">
                    <span class="text-gray-700">{{ Auth::guard('admin')->user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-50 py-1 mt-2 w-48 bg-white rounded-md ring-1 ring-black ring-opacity-5 shadow-lg">

                    <a href="{{ route('admin.profile.edit') }}"
                       class="block px-4 py-2 text-sm text-gray-700 transition-colors duration-200 hover:bg-gray-100">
                        <i class="mr-2 fas fa-user"></i> Edit Profile
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                                class="block px-4 py-2 w-full text-sm text-left text-gray-700 transition-colors duration-200 hover:bg-gray-100">
                            <i class="mr-2 fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
