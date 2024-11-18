@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8">About OGITECH Cooperative</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div>
            <h2 class="text-2xl font-bold mb-4">Our Story</h2>
            <p class="text-gray-700 mb-4">
                OGITECH Cooperative was established to serve the financial needs of our community members.
                We believe in the power of collective growth and mutual support.
            </p>
        </div>
        <div>
            <h2 class="text-2xl font-bold mb-4">Our Mission</h2>
            <p class="text-gray-700 mb-4">
                To provide accessible financial services and promote economic well-being among our members
                through sustainable cooperative practices.
            </p>
        </div>
    </div>
</div>
@endsection
