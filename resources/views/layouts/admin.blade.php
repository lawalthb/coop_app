<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('styles')




    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Add this right after the <body> tag -->
    <x-flash-messages />
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-100">
        <!-- Overlay -->
        <div x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden">
        </div>

        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-purple-800 text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="p-6 border-b border-purple-700">
                <h2 class="text-2xl font-bold">OGITECH COOP</h2>
            </div>

            <nav class="mt-6">
                <div class="px-4 py-2 text-purple-200 text-sm uppercase tracking-wider">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.members') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.members*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Members</span>
                </a>

                <a href="{{ route('admin.entrance-fees') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.entrance-fees*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-3">Entrance Fee</span>
                </a>

                <a href="{{ route('admin.saving-types.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.saving-types*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-coins w-5"></i>
                    <span class="ml-3">Saving Types</span>
                </a>


                <a href="{{ route('admin.savings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.savings*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-piggy-bank w-5"></i>
                    <span class="ml-3">Savings</span>
                </a>


                <a href="{{ route('admin.loans') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.loans*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span class="ml-3">Loans</span>
                </a>

                <a href="{{ route('admin.transactions') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.transactions*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="ml-3">Transactions</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.reports*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-3">Reports</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.settings*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span class="ml-3">Settings</span>
                </a>
            </nav>
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side with welcome message -->
                        <div class="flex items-center">
                            <div class="lg:hidden">
                                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-bars text-xl"></i>
                                </button>
                            </div>
                            <h2 class="ml-4 text-xl text-gray-800">Welcome, {{ auth()->user()->firstname }}!</h2>
                        </div>

                        <!-- Right side profile dropdown -->
                        <div class="flex items-center">
                            <div x-data="{ isOpen: false }" class="relative">
                                <button @click="isOpen = !isOpen" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100 focus:outline-none">
                                    <img src="{{ asset('storage/' . auth()->user()->member_image) }}" alt="Admin Avatar" class="w-8 h-8 rounded-full">
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
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
    @yield('scripts')

</body>

</html>
