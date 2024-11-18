@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="bg-purple-50">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-purple-800 mb-4">Welcome to OGITECH Cooperative</h1>
            <p class="text-xl text-gray-600 mb-8">Building financial futures together through cooperation and support</p>
            <a href="{{ route('register') }}" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700">Join Us Today</a>
        </div>
    </div>
</div>
    <!-- Features Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
</div>
@endsection
