<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OGITECH COOP') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_co.jpg') }}">

    <!-- Open Graph / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="OGITECH Academic Staff Cooperative Society">
    <meta property="og:description" content="Building financial futures together through cooperation and support">
    <meta property="og:image" content="{{ asset('images/logo_co.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Custom Scrollbar Styling -->
    <style>
        /* Webkit browsers (Chrome, Safari, newer versions of Opera) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9; /* Light purple/gray background */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #7e22ce; /* Purple color matching the theme */
            border-radius: 10px;
            transition: background 0.3s;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b21a8; /* Darker purple on hover */
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #7e22ce #f1f5f9;
        }

        /* For the sidebar specifically */
        #sidebar {
            scrollbar-width: thin;
            scrollbar-color: #6b21a8 #4c1d95;
        }

        #sidebar::-webkit-scrollbar-track {
            background: #4c1d95; /* Darker purple for sidebar track */
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #6b21a8; /* Slightly lighter purple for thumb */
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #7e22ce; /* Even lighter on hover */
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-purple-50">
        <!-- Mobile Menu Button and Welcome Text -->
        <div class="lg:hidden flex items-center justify-between w-full px-4 h-16 bg-white shadow-sm fixed top-0 left-0 z-40">
            <button id="mobile-menu-button" class="text-purple-600 hover:text-purple-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
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
                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.profile') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Profile</span>
                    </a>
                    <a href="{{ route('member.savings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.savings') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-piggy-bank w-5"></i>
                        <span class="ml-3">Savings</span>
                    </a>
                    <a href="{{ route('member.withdrawals.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.withdrawals.*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-hand-holding-usd w-5"></i>
                        <span class="ml-3">Withdrawals</span>
                    </a>

                    <a href="{{ route('member.shares.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.shares*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-3">Shares</span>
                    </a>

                    <a href="{{ route('member.loans.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.loans*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-money-bill-wave w-5"></i>
                        <span class="ml-3">Loans</span>
                    </a>

                    <a href="{{ route('member.transactions.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.transactions*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-exchange-alt w-5"></i>
                        <span class="ml-3">Passbook</span>
                    </a>
                    <a href="{{ route('member.resources.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('member.resources*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-3">Information</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('notification') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-3">Notifications
                            @if(auth()->user()->notifications()->whereNull('read_at')->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full"> {{ auth()->user()->notifications()->whereNull('read_at')->count() }}</span>
                            @endif
                        </span>
                    </a>
                    <a href="{{ route('member.commodities.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('commodities*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-shopping-basket w-5"></i>
    <span class="ml-3">Commodities</span>
</a>

<a href="{{ route('member.commodity-subscriptions.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('commodity-subscriptions*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-clipboard-list w-5"></i>
    <span class="ml-3">My Subscriptions</span>
</a>

                    <!-- Add this to your member sidebar navigation -->
                    <a href="{{ route('member.guarantor-requests') }}"
                        class="flex items-center px-4 py-2 text-white-700 hover:bg-purple-700 {{ request()->routeIs('member.guarantor-requests*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-handshake w-5 h-5 mr-3"></i>
                        <span>Guarantor Requests</span>
                        @if(auth()->user()->pendingGuarantorRequests()->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ auth()->user()->pendingGuarantorRequests()->count() }}
                        </span>
                        @endif
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
                            <span class="text-gray-800 font-medium">Welcome, {{ auth()->user()->title }} {{ auth()->user()->surname }}</span>
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
            <footer class="bg-white shadow-inner mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 text-sm">
                            © {{ date('Y') }} OGITECH COOP. All rights reserved.
                        </p>
                        <div class="flex items-center space-x-4 text-gray-400">
                            <span class="text-sm">Version 1.0</span>
                            <span class="text-sm">Powered by OGITECH COOP</span>
                        </div>
                    </div>
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
