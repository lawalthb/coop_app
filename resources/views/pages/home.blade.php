@extends('layouts.app')
@section('title', 'Home | Welcome to OGITECH Cooperative')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@section('content')
<div class="bg-purple-50">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <img src="{{ asset('images/og_coop.png') }}" alt="OGITECH COOP Logo" class="h-32 w-auto mx-auto mb-8">
            <h1 class="text-4xl font-bold text-purple-800 mb-4">Welcome to OGITECH Academic Staff Cooperative Multipurpose Society</h1>
            <h3 class="text-3xl font-bold text-purple-800 mb-4">AKA: ASUP Cooperative Society</h3>
            <p class="text-xl text-gray-600 mb-8">Building financial futures together through cooperation and support</p>
            <a href="{{ route('register') }}" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700">Join Us Today</a>
        </div>
    </div>
</div>
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-purple-800 mb-6">Membership Information</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                Membership is open to any staff member of the Ogun State Institute of Technology, Igbesa who is above the age of eighteen (18) years. Please get in touch with the Secretariat for other terms and conditions.
            </p>
        </div>
    </div>
</div>
<!-- Features Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-center gap-4 mb-12">
            <i class="fas fa-coins text-4xl text-purple-600"></i>
            <h2 class="text-3xl font-bold text-purple-800 text-center">Our Core Services</h2>
            <i class="fas fa-coins text-4xl text-purple-600"></i>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <!-- Savings -->
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-purple-600 mb-4 text-center">
                    <i class="fas fa-piggy-bank text-6xl mb-2"></i>
                    <div class="flex justify-center gap-4 mt-4">
                        <i class="fas fa-wallet text-2xl"></i>
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                        <i class="fas fa-coins text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-purple-800 mb-4">Savings</h3>
                <p class="text-gray-600 mb-4">Secure your future with our flexible savings options. Earn competitive returns while building your financial security.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Regular Savings</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Fixed Deposits</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Target Savings</li>
                </ul>
            </div>

            <!-- Loans -->
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-purple-600 mb-4 text-center">
                    <i class="fas fa-hand-holding-usd text-6xl mb-2"></i>
                    <div class="flex justify-center gap-4 mt-4">
                        <i class="fas fa-percentage text-2xl"></i>
                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                        <i class="fas fa-handshake text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-purple-800 mb-4">Loans</h3>
                <p class="text-gray-600 mb-4">Access quick loans with competitive interest rates. Fast approval process and flexible repayment terms.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Regular Loans</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Emergency Loans</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Project Financing</li>
                </ul>
            </div>

            <!-- Investment -->
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-purple-600 mb-4 text-center">
                    <i class="fas fa-chart-line text-6xl mb-2"></i>
                    <div class="flex justify-center gap-4 mt-4">
                        <i class="fas fa-chart-pie text-2xl"></i>
                        <i class="fas fa-university text-2xl"></i>
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-purple-800 mb-4">Investment</h3>
                <p class="text-gray-600 mb-4">Grow your wealth with our diverse investment opportunities. Expert guidance for maximum returns.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Fixed Returns</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Property Investment</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Business Ventures</li>
                </ul>
            </div>
        </div>
    </div>
</div>



<!-- After existing sections, add: -->

<!-- Success Stories Section -->
<div class="bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-purple-800 text-center mb-12">Member Success Stories</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/eletu.jpg') }}" alt="Member" class="w-16 h-16 rounded-full">
                    <div class="ml-4">
                        <h4 class="font-bold">John D.</h4>
                        <p class="text-sm text-gray-600">Faculty Member</p>
                    </div>
                </div>
                <p class="text-gray-700">"Through the cooperative's loan program, I was able to fund my doctoral research and advance my career."</p>
            </div>
            <!-- Add 2 more success stories -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/eletu.jpg') }}" alt="Member" class="w-16 h-16 rounded-full">
                    <div class="ml-4">
                        <h4 class="font-bold">John D.</h4>
                        <p class="text-sm text-gray-600">Faculty Member</p>
                    </div>
                </div>
                <p class="text-gray-700">"Through the cooperative's loan program, I was able to fund my doctoral research and advance my career."</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/eletu.jpg') }}" alt="Member" class="w-16 h-16 rounded-full">
                    <div class="ml-4">
                        <h4 class="font-bold">John D.</h4>
                        <p class="text-sm text-gray-600">Faculty Member</p>
                    </div>
                </div>
                <p class="text-gray-700">"Through the cooperative's loan program, I was able to fund my doctoral research and advance my career."</p>
            </div>


        </div>
    </div>
</div>
<!-- Key Statistics Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <span class="block text-4xl font-bold text-purple-800 mb-2">500+</span>
                <span class="text-gray-600">Active Members</span>
            </div>
            <div>
                <span class="block text-4xl font-bold text-purple-800 mb-2">₦50M+</span>
                <span class="text-gray-600">Loans Disbursed</span>
            </div>
            <div>
                <span class="block text-4xl font-bold text-purple-800 mb-2">15+</span>
                <span class="text-gray-600">Years of Service</span>
            </div>
            <div>
                <span class="block text-4xl font-bold text-purple-800 mb-2">98%</span>
                <span class="text-gray-600">Member Satisfaction</span>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-purple-800 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Ready to Secure Your Financial Future?</h2>
        <p class="text-xl mb-8">Join OGITECH Cooperative today and start your journey towards financial freedom.</p>
        <div class="space-x-4">
            <a href="{{ route('register') }}" class="inline-block bg-white text-purple-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">Register Now</a>
            <a href="{{ route('contact') }}" class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700">Contact Us</a>
        </div>
    </div>
</div>

<!-- Executive Section -->
<div class="bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-purple-800 text-center mb-12">Our Executive Team</h2>
        <!-- Grid container with responsive columns -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- President card spanning 2 columns on large screens -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center sm:col-span-2 lg:col-span-3">
                <div class="p-4">
                    <img src="{{ asset('images/president.jpg') }}" alt="President" class="w-48 h-48 rounded-full mx-auto object-cover">
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-purple-800">Mr. Ilo, Hammed Owolabi</h3>
                    <p class="text-purple-600 font-semibold mb-4 text-lg">President</p>
                    <p class="text-gray-600">Leading with vision and integrity</p>
                </div>
            </div>

            <!-- Other 4 executives in 2x2 grid -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center">
                <div class="p-4">
                    <img src="{{ asset('images/eletu.jpg') }}" alt="Secretary" class="w-32 h-32 rounded-full mx-auto object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-purple-800">Mr. Eletu, Ahmed Ajiboye</h3>
                    <p class="text-purple-600 font-semibold mb-2">General Secretary</p>
                    <p class="text-gray-600 text-sm">Ensuring efficient administration</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center">
                <div class="p-4">
                    <img src="{{ asset('images/akinola.jpg') }}" alt="Treasurer" class="w-32 h-32 rounded-full mx-auto object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-purple-800">Engr. Akinola, Akinyemi Adedeji</h3>
                    <p class="text-purple-600 font-semibold mb-2">Treasurer</p>
                    <p class="text-gray-600 text-sm">Managing financial stability</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center">
                <div class="p-4">
                    <img src="{{ asset('images/ajayi.jpg') }}" alt="PRO" class="w-32 h-32 rounded-full mx-auto object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-purple-800">Mr. Ajayi, John Olarewaju</h3>
                    <p class="text-purple-600 font-semibold mb-2">Public Relations Officer</p>
                    <p class="text-gray-600 text-sm">Fostering community relations</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center">
                <div class="p-4">
                    <img src="{{ asset('images/sadiq.jpg') }}" alt="PRO" class="w-32 h-32 rounded-full mx-auto object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-purple-800">Mr. Moshood, Obasanjo Sadiq</h3>
                    <p class="text-purple-600 font-semibold mb-2">Welfare Officer</p>
                    <p class="text-gray-600 text-sm">Wellbeing of society members</p>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Splide('#success-stories', {
            type: 'loop',
            perPage: 3,
            autoplay: true,
            interval: 3000,
            gap: '2rem',
            breakpoints: {
                768: {
                    perPage: 2,
                },
                640: {
                    perPage: 1,
                }
            }
        }).mount();
    });
</script>
@endpush
