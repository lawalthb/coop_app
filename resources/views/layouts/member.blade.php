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
    <div class="min-h-screen flex bg-purple-50">
        <!-- Sidebar for Desktop -->
        <div id="sidebar" class="hidden lg:flex lg:flex-col lg:w-64 bg-purple-800 text-white">
            <div class="p-6 border-b border-purple-700">
                <h2 class="text-2xl font-bold">OGITECH COOP</h2>
            </div>
            <nav class="flex-1 mt-6"></nav>
                <div class="px-4 py-2 text-purple-200 text-sm uppercase tracking-wider">Menu</div>
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

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-40 hidden">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="fixed inset-y-0 left-0 w-64 bg-purple-800 text-white">
                <!-- Same content as desktop sidebar -->
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Mobile menu button -->
                        <button class="lg:hidden p-4" id="mobile-menu-button">
                            <i class="fas fa-bars text-purple-600"></i>
                        </button>

                        <div class="flex items-center">
                            <span class="text-gray-800 font-medium">Welcome, {{ auth()->user()->firstname }}</span>
                        </div>

                        <div class="flex items-center">
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

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @if(!auth()->user()->admin_sign)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Account Pending Approval</p>
                        <p>Your account is currently pending admin approval. Some features may be restricted.</p>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-inner py-4 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-gray-500 text-sm">
                        © {{ date('Y') }} OGITECH COOP. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        const mobileButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');

        mobileButton.addEventListener('click', () => {
            mobileSidebar.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!mobileSidebar.contains(e.target) && !mobileButton.contains(e.target)) {
                mobileSidebar.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
