@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('commodities.index') }}" class="text-purple-600 hover:text-purple-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Commodities
        </a>
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

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                @if($commodity->image)
                <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="w-full h-64 md:h-full object-cover">
                @else
                <div class="w-full h-64 md:h-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-shopping-basket text-gray-400 text-6xl"></i>
                </div>
                @endif
            </div>

            <div class="md:w-1/2 p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $commodity->name }}</h1>

                <div class="flex items-center mb-4">
                    <span class="text-2xl font-bold text-purple-700 mr-2">₦{{ number_format($commodity->price, 2) }}</span>
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                        {{ $commodity->quantity_available }} available
                    </span>
                </div>

                @if($commodity->start_date || $commodity->end_date)
                <div class="mb-4 text-sm text-gray-600">
                    @if($commodity->start_date)
                    <div><span class="font-medium">Start Date:</span> {{ $commodity->start_date->format('M d, Y') }}</div>
                    @endif
                    @if($commodity->end_date)
                    <div><span class="font-medium">End Date:</span> {{ $commodity->end_date->format('M d, Y') }}</div>
                    @endif
                </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700">{{ $commodity->description }}</p>
                </div>

                <form action="{{ route('commodities.subscribe', $commodity) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="{{ $commodity->quantity_available }}" value="1" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        <p class="text-sm text-gray-500 mt-1">Maximum available: {{ $commodity->quantity_available }}</p>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Subscription (Optional)</label>
                        <textarea name="reason" id="reason" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                  style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Subscribe Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
