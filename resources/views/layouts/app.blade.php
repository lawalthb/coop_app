<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGITECH Cooperative - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="bg-purple-600 text-white">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="text-xl font-bold">OGITECH COOP</a>
                <div class="space-x-4">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('about') }}">About</a>
                    <a href="{{ route('services') }}">Services</a>
                    <a href="{{ route('resources') }}">Resources</a>
                    <a href="{{ route('events') }}">Events</a>
                    <a href="{{ route('contact') }}">Contact</a>
                    @guest
                        <a href="{{ route('login') }}" class="btn">Login</a>
                        <a href="{{ route('register') }}" class="btn">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-purple-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">OGITECH COOP</h3>
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
