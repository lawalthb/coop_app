@extends('layouts.app')
@section('title', 'Events')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8">Upcoming Events</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 px-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="mb-4">
                <span class="text-blue-600 font-bold">JAN 15, 2024</span>
            </div>
            <h3 class="text-xl font-bold mb-2">Annual General Meeting</h3>
            <p class="text-gray-600 mb-4">Join us for our annual general meeting where we'll discuss our achievements and future plans.</p>
            <a href="#" class="text-blue-600 hover:underline">Learn More →</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="mb-4">
                <span class="text-blue-600 font-bold">FEB 1, 2024</span>
            </div>
            <h3 class="text-xl font-bold mb-2">Financial Workshop</h3>
            <p class="text-gray-600 mb-4">Learn about personal finance management and investment strategies.</p>
            <a href="#" class="text-blue-600 hover:underline">Learn More →</a>
        </div>
    </div>
</div>
@endsection
