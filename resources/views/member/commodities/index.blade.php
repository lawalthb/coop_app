@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Available Commodities</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($commodities as $commodity)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative">
                @if($commodity->image)
                <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-shopping-basket text-gray-400 text-4xl"></i>
                </div>
                @endif

                @if($commodity->end_date && $commodity->end_date->diffInDays(now()) < 7)
                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    Ends in {{ number_format( $commodity->end_date->diffInDays(now()),0) }} days
                </div>
                @endif
            </div>

            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $commodity->name }}</h2>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $commodity->description }}</p>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-purple-700 font-bold">₦{{ number_format($commodity->price, 2) }}</span>
                    <span class="text-sm text-gray-500">{{ $commodity->quantity_available }} available</span>
                </div>

                <a href="{{ route('member.commodities.show', $commodity) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 rounded-md transition duration-300">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
            <i class="fas fa-shopping-basket text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Commodities Available</h3>
            <p class="text-gray-500">There are currently no commodities available for subscription.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $commodities->links() }}
    </div>
</div>
@endsection
