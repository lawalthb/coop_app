<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_co.jpg') }}">

    <!-- Open Graph / WhatsApp tes-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="OGITECH Academic Staff Cooperative Society">
    <meta property="og:description" content="Building financial futures together through cooperation and support">
    <meta property="og:image" content="{{ asset('images/logo_co.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <x-flash-messages />
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-100">
        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden"></div>

        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-30 w-64 min-w-[16rem] bg-purple-800 text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="p-6 border-b border-purple-700">
                <h2 class="text-2xl font-bold">OGITECH COOP</h2>
            </div>
            <nav class="mt-6">
                <div class="px-4 py-2 text-purple-200 text-sm uppercase tracking-wider">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.entrance-fees') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.entrance-fees*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-3">Entrance Fee</span>
                </a>
                <a href="{{ route('admin.members') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.members*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Members</span>
                </a>

                <a href="{{ route('admin.profile-updates.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.profile-updates.index*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-user-edit w-5"></i>
                    <span class="ml-3">Update Requests</span>
                </a>


                </a>

                <a href="{{ route('admin.saving-types.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.saving-types*') ? 'bg-purple-700' : '' }}">

                    <i class="fas fa-coins w-5"></i>
                    <span class="ml-3">Saving Types</span>
                </a>
                <a href="{{ route('admin.savings') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.savings*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-piggy-bank w-5"></i>
                    <span class="ml-3">Savings</span>
                </a>

                  <a href="{{ route('admin.savings.settings.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700  {{ request()->routeIs('admin.savings.settings.*') ?  'bg-purple-700' : '' }}">
        <i class="fas fa-cog mr-3"></i>
        <span>Savings Settings</span>
    </a>


                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.withdrawals.index') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="ml-3">Process Withdrawal</span>
</a>

                <a href="{{ route('admin.share-types.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.share-types*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span class="ml-3">Share Types</span>
                </a>

                <a href="{{ route('admin.shares.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.shares*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="ml-3">Shares</span>
                </a>
                <a href="{{ route('admin.loan-types.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.loan-types*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-list-alt w-5"></i>
                    <span class="ml-3">Loan Types</span>
                </a>
                <a href="{{ route('admin.loans.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.loans*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span class="ml-3">Loans</span>
                </a>
                <a href="{{ route('admin.transactions.index')}}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.transactions*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="ml-3">Transactions</span>
                </a>
                <a href="{{ route('admin.reports.transaction-summary') }}" class="flex items-center px-4 py-3 hover:bg-purple-700  {{ request()->routeIs('admin.reports.transaction-summary*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-chart-bar mr-3"></i>
    <span>Transaction Summary</span>
</a>


                <a href="{{ route('admin.resources.index')}}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.resources*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Information</span>
                </a>
                <a href="{{ route('admin.commodities.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.commodities*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-shopping-basket w-5"></i>
    <span class="ml-3">Commodities</span>
</a>
<a href="{{ route('admin.commodity-subscriptions.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.commodity-subscriptions*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-clipboard-list w-5"></i>
    <span class="ml-3">Commodity Subscriptions</span>
</a>
<a href="{{ route('admin.commodity-payments.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700 {{ request()->routeIs('admin.commodity-payments*') ? 'bg-purple-700' : '' }}">
    <i class="fas fa-money-bill-wave w-5"></i>
    <span class="ml-3">Commodity Payments</span>
</a>
                <a href="{{ route('admin.faqs.index') }}"
                    class="flex items-center px-4 py-2 text-white-700 hover:bg-purple-700 {{ request()->routeIs('admin.faqs.*') ? 'bg-purple-700' : '' }}">
                    <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
                    <span>FAQ Management</span>
                </a>
                <!-- Reports Section -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="px-4 text-xs font-semibold text-white-500 uppercase tracking-wider">
                        Reports
                    </h3>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2 text-white-700 hover:bg-purple-700 {{ request()->routeIs('admin.reports.*') ? 'bg-purple-700' : '' }}">
                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                        <span>Reports</span>
                    </a>
                      <a href="{{ route('admin.financial-summary.index') }}" class="flex items-center px-4 py-3 hover:bg-purple-700  {{ request()->routeIs('admin.financial-summary.*') ?  'bg-purple-700' : ''}}">
        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
        <span>Financial Summary</span>
    </a>
                </div>


                <!-- Add these items to your sidebar navigation -->
                <div class="space-y-1">
                    <!-- Existing sidebar items -->

                    <!-- Admin Management Section -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="px-4 text-xs font-semibold text-white-500 uppercase tracking-wider">
                            System Administration
                        </h3>

                        @if(auth()->user()->hasPermission('view_users'))
                        <a href="{{ route('admin.admins.index') }}"
                            class="flex items-center px-4 py-2 text-white-700 hover:bg-purple-700 {{ request()->routeIs('admin.admins.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
                            <span>Admin Users</span>
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('view_roles'))
                        <a href="{{ route('admin.roles.index') }}"
                            class="flex items-center px-4 py-2 text-white-700 hover:bg-purple-700 {{ request()->routeIs('admin.roles.*') ? 'bg-purple-700' : '' }}">
                            <i class="fas fa-user-shield w-5 h-5 mr-3"></i>
                            <span>Roles & Permissions</span>
                        </a>
                        @endif
                        <!-- <a href="#" class="flex items-center px-4 py-3 hover:bg-purple-700 'bg-purple-700' : '' }}">
                            <i class="fas fa-cog w-5"></i>
                            <span class="ml-3">Settings</span>
                        </a> -->
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0">
            <!-- Top Navigation -->

            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="lg:hidden">
                                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-bars text-xl"></i>
                                </button>
                            </div>
                            <h2 class="ml-4 text-xl text-gray-800">Welcome, {{ auth()->user()->firstname }}!</h2>
                        </div>
                        <div class="flex items-center">
                            <!-- Notification Icon -->

                            <a href="{{ route('notifications.index') }}" class="relative mr-4">
                                <i class="fas fa-bell text-gray-600 text-xl"></i>
                                @if(auth()->user()->notifications()->whereNull('read_at')->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                    {{ auth()->user()->notifications()->whereNull('read_at')->count() }}
                                </span>
                                @endif
                            </a>

                            <!-- Existing Profile Dropdown -->
                            <div x-data="{ isOpen: false }" class="relative">
                                <button @click="isOpen = !isOpen" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100 focus:outline-none">
                                    <img src="{{ asset('storage/' . auth()->user()->member_image) }}" alt="Admin Avatar" class="w-8 h-8 rounded-full">
                                    <span class="text-gray-700">{{ auth()->user()->firstname }}</span>
                                    <svg class="w-4 h-4 text-gray-500" :class="{'rotate-180': isOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="isOpen" @click.away="isOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
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
            <main class="flex-1 overflow-x-auto">
                <div class="py-10 px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
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
</body>

</html>
