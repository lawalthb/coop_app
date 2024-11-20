@extends('layouts.app')
@section('title', 'Home | Welcome to OGITECH Cooperative')
@section('content')
<div class="bg-purple-50">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <!-- <img src="{{ asset('images/logo_co.jpg') }}" alt="OGITECH COOP Logo" class="h-32 w-auto mx-auto mb-8"> -->
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
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 px-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Savings</h3>
            <p>Secure your future with our flexible savings options</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Loans</h3>
            <p>Access quick loans with competitive interest rates</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Investment</h3>
            <p>Grow your wealth with our investment opportunities</p>
        </div>
    </div>
</div>
@endsection
