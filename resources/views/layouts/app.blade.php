<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGITECH Cooperative - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- In your app.blade.php head section -->


</head>

<body>
    <nav class="bg-purple-600 text-white">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="flex justify-between items-center">
                    <a href="/" class="text-xl font-bold">OGITECH COOP.</a>
                    <button class="md:hidden" onclick="toggleMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden md:flex md:space-x-4" id="mobile-menu">
                    <a href="{{ route('home') }}" class="block py-2 md:py-0">Home</a>
                    <a href="{{ route('about') }}" class="block py-2 md:py-0">About</a>
                    <a href="{{ route('services') }}" class="block py-2 md:py-0">Services</a>
                    <a href="{{ route('resources') }}" class="block py-2 md:py-0">Resources</a>
                    <a href="{{ route('events') }}" class="block py-2 md:py-0">Events</a>
                    <a href="{{ route('contact') }}" class="block py-2 md:py-0">Contact</a>
                    @guest
                    <a href="{{ route('login') }}" class="block py-2 md:py-0 bg-purple-700 px-4 rounded">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 md:py-0 bg-purple-700 px-4 rounded">Register</a>
                    @else
                    <a href="{{ route('dashboard') }}" class="block py-2 md:py-0">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
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
                    <h3 class="text-lg font-bold mb-4">Working Hours</h3>
                    <ul class="space-y-2 text-purple-100">
                        <li>Monday - Friday: 8:00 AM - 4:00 PM</li>
                        <li>Saturday: 9:00 AM - 1:00 PM</li>
                        <li>Sunday: Closed</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-purple-700 mt-8 pt-8 text-center text-purple-100">
                <p>© {{ date('Y') }} OGITECH Cooperative. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
