<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OGITECH COOP') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-purple-50">
        <!-- Mobile Menu Button and Welcome Text -->
        <div class="lg:hidden flex items-center justify-between w-full px-4 h-16 bg-white shadow-sm fixed top-0 left-0 z-40">
            <button id="mobile-menu-button" class="text-purple-600 hover:text-purple-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-gray-800 font-medium">Welcome, {{ auth()->user()->firstname }}</span>
            <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-purple-600">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-purple-800 text-white transition-transform duration-200 ease-in-out z-30">
            <div class="flex flex-col h-full">
                <div class="p-6 border-b border-purple-700">
                    <h2 class="text-2xl font-bold">OGITECH COOP</h2>
                </div>
                <nav class="flex-1 overflow-y-auto">
                    <div class="px-4 py-2 text-purple-200 text-sm uppercase tracking-wider">Member</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.dashboard') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="{{ route('member.profile') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.profile') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Profile</span>
                    </a>
                    <a href="{{ route('member.savings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.savings') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-piggy-bank w-5"></i>
                        <span class="ml-3">Savings</span>
                    </a>
                    <a href="{{ route('member.transactions') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.transactions') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-exchange-alt w-5"></i>
                        <span class="ml-3">Transactions</span>
                    </a>
                    <a href="{{ route('member.documents') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.documents') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-3">Documents</span>
                    </a>
                    <a href="{{ route('member.notifications') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.notifications') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-3">Notifications</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <span class="text-gray-800 font-medium">Welcome, {{ auth()->user()->firstname }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center text-gray-600 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
                @if(!auth()->user()->admin_sign)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Account Pending Approval</p>
                        <p>Your account is currently pending admin approval. Some features may be restricted.</p>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-inner py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-gray-500 text-sm">
                        © {{ date('Y') }} OGITECH COOP. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (!sidebar.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>
