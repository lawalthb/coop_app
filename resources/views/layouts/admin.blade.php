<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-purple-800 text-white transition-transform duration-200 ease-in-out transform -translate-x-full lg:translate-x-0">
            <div class="p-6 border-b border-purple-700">
                <h2 class="text-2xl font-bold">OGITECH COOP.</h2>
            </div>

            <nav class="mt-6">
                <div class="px-4 py-2 text-purple-200 text-sm uppercase tracking-wider">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.members') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Members</span>
                </a>
                <a href="{{ route('admin.loans') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span class="ml-3">Loans</span>
                </a>
                <a href="{{ route('admin.savings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-piggy-bank w-5"></i>
                    <span class="ml-3">Savings</span>
                </a>
                <a href="{{ route('admin.transactions') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="ml-3">Transactions</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-3">Reports</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700">
                    <i class="fas fa-cog w-5"></i>
                    <span class="ml-3">Settings</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button id="mobile-menu-button" class="lg:hidden text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                        </div>
                        <div class="flex items-center">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900">
                                    <span class="mr-2">Admin</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-10 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Add Alpine.js in the head section -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Replace the profile dropdown section with this improved version -->
    <div class="flex items-center">
        <div x-data="{ isOpen: false }" class="relative">
            <button @click="isOpen = !isOpen" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100 focus:outline-none">
                <img src="{{ asset('images/avatar.png') }}" alt="Admin Avatar" class="w-8 h-8 rounded-full">
                <span class="text-gray-700">{{ auth()->user()->firstname }}</span>
                <svg class="w-4 h-4 text-gray-500" :class="{'rotate-180': isOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="isOpen"
                 @click.away="isOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">

                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
