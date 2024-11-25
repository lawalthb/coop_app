<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGITECH Cooperative - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_co.jpg') }}">

    <!-- Open Graph / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="OGITECH Academic Staff Cooperative Society">
    <meta property="og:description" content="Building financial futures together through cooperation and support">
    <meta property="og:image" content="{{ asset('images/logo_co.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">


    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="OGITECH Academic Staff Cooperative Society">
    <meta property="twitter:description" content="Building financial futures together through cooperation and support">
    <meta property="twitter:image" content="{{ asset('images/logo_co.jpg') }}">
    <meta property="twitter:image:width" content="1200">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Top Bar -->
    <div class="bg-purple-900 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <span><i class="fas fa-phone-alt mr-2"></i> +234 XXX XXX XXXX</span>
                    <span><i class="fas fa-envelope mr-2"></i> info@ogitechcoop.com</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="hover:text-purple-200"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-purple-200"><i class="fab fa-whatsapp"></i></a>

                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-purple-600 text-white sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="flex justify-between items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo_co.jpg') }}" alt="OGITECH COOP" class="h-12 w-auto">
                        <div>
                            <span class="text-xl font-bold block">OGITECH COOP</span>
                            <span class="text-sm text-purple-200">Building futures together</span>
                        </div>
                    </a>
                    <button class="md:hidden focus:outline-none" onclick="toggleMenu()">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>

                <div class="hidden md:flex md:items-center md:space-x-6" id="mobile-menu">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle mr-1"></i> About
                    </a>
                    <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd mr-1"></i> Services
                    </a>
                    <a href="{{ route('resources') }}" class="nav-link {{ request()->routeIs('resources') ? 'active' : '' }}">
                        <i class="fas fa-book mr-1"></i> Resources
                    </a>
                    <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt mr-1"></i> Events
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope mr-1"></i> Contact
                    </a>
                    @guest
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="bg-purple-700 hover:bg-purple-800 px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-purple-600 hover:bg-purple-100 px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    </div>
                    @else
                    <a href="{{ route('member.dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <style>
        .nav-link {
            @apply px-3 py-2 rounded-lg hover:bg-purple-700 transition-colors;
        }

        .nav-link.active {
            @apply bg-purple-700;
        }
    </style>
    <main>
        @yield('content')
    </main>

    <footer class="bg-purple-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div>
                        <img src="{{ asset('images/logo.jpg') }}" alt="OGITECH COOP" class="h-12 w-auto">
                    </div>
                    <p class="text-purple-100">Building financial futures together through cooperation and support</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="text-purple-100 hover:text-white">About Us</a></li>
                        <li><a href="{{ route('services') }}" class="text-purple-100 hover:text-white">Services</a></li>
                        <li><a href="{{ route('contact') }}" class="text-purple-100 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-purple-100">
                        <li>OGITECH Campus, Igbesa</li>
                        <li>Phone: +234 XXX XXX XXXX</li>
                        <li>Email: info@ogitechcoop.com</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Meeting Hours</h3>
                    <ul class="space-y-2 text-purple-100">
                        <li>Thursday: 12:00 PM - 1:00 PM</li>
                        <li>Saturday (Online): 7:00PM - 8:00PM</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-purple-700 mt-8 pt-8 text-center text-purple-100">
                <p>© {{ date('Y') }} OGITECH Cooperative. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // Mobile menu functionality
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuButton = document.querySelector('[onclick="toggleMenu()"]');

        function toggleMenu() {
            // Toggle mobile menu visibility
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex', 'flex-col', 'w-full', 'py-4', 'absolute', 'top-full', 'left-0', 'bg-purple-800', 'shadow-lg');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex', 'flex-col', 'w-full', 'py-4', 'absolute', 'top-full', 'left-0', 'bg-purple-800', 'shadow-lg');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
    <button id="scrollToTop" class="fixed bottom-8 right-8 bg-purple-600 text-white p-4 rounded-full shadow-lg hover:bg-purple-700 transition-all duration-300 opacity-0 invisible">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scroll to top functionality
        const scrollButton = document.getElementById('scrollToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollButton.classList.remove('opacity-0', 'invisible');
                scrollButton.classList.add('opacity-100', 'visible');
            } else {
                scrollButton.classList.add('opacity-0', 'invisible');
                scrollButton.classList.remove('opacity-100', 'visible');
            }
        });

        scrollButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>

</html>
