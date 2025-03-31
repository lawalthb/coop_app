@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('commodity-subscriptions.index') }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to My Subscriptions
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Subscription Details</h2>
                <span class="px-3 py-1 text-sm rounded-full
                    @if($subscription->status === 'approved') bg-green-100 text-green-800
                    @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-8">
                <!-- Reference Number -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-hashtag text-gray-400 mr-3"></i>
                        <div>
                            <span class="text-sm text-gray-500">Reference Number:</span>
                            <p class="font-medium text-gray-900">{{ $subscription->reference }}</p>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout for Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Commodity Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Commodity Information</h3>

                        <!-- Commodity Details with Image -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                @if($subscription->commodity->image)
                                <img src="{{ asset('storage/' . $subscription->commodity->image) }}" alt="{{ $subscription->commodity->name }}" class="w-24 h-24 object-cover rounded-lg shadow">
                                @else
                                <div class="w-24 h-24 bg-gray-200 rounded-lg shadow flex items-center justify-center">
                                    <i class="fas fa-shopping-basket text-gray-400 text-3xl"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $subscription->commodity->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($subscription->commodity->description, 100) }}</p>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Unit Price:</span>
                                <span class="font-medium">₦{{ number_format($subscription->commodity->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Quantity:</span>
                                <span class="font-medium">{{ $subscription->quantity }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Total Amount:</span>
                                <span class="font-bold text-purple-700">₦{{ number_format($subscription->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-gray-400 w-5 mr-2"></i>
                                <span class="text-sm text-gray-500">Subscription Date:</span>
                                <span class="ml-2 font-medium">{{ $subscription->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($subscription->status !== 'pending')
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 w-5 mr-2"></i>
                                <span class="text-sm text-gray-500">Last Updated:</span>
                                <span class="ml-2 font-medium">{{ $subscription->updated_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Status Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Status Information</h3>

                        <!-- Status Badge -->
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-3">Current Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($subscription->status === 'approved') bg-green-100 text-green-800
                                @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>

                        <!-- Status Message -->
                        @if($subscription->status === 'pending')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Your subscription is pending approval. You will be notified once it's processed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @elseif($subscription->status === 'approved')
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        Your subscription has been approved. You can collect your commodity from the cooperative office.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @elseif($subscription->status === 'rejected')
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Your subscription has been rejected. Please see the admin notes below for more information.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Reason for Subscription -->
                        @if($subscription->reason)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Your Reason for Subscription:</h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700">{{ $subscription->reason }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Admin Notes -->
                        @if($subscription->admin_notes)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Admin Notes:</h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700">{{ $subscription->admin_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Timeline</h3>
                    <div class="space-y-6">
                        <!-- Created Event -->
                        <div class="relative pl-8 pb-6">
                            <div class="absolute top-0 left-0 h-full w-0.5 bg-gray-200"></div>
                            <div class="absolute top-0 left-0 -ml-1.5 mt-1.5 h-3 w-3 rounded-full border-2 border-purple-500 bg-white"></div>
                            <div class="flex flex-col">
                                <h4 class="text-sm font-medium text-gray-900">Subscription Created</h4>
                                <time datetime="{{ $subscription->created_at }}" class="text-xs text-gray-500">
                                    {{ $subscription->created_at->format('M d, Y h:i A') }}
                                </time>
                                <p class="mt-1 text-sm text-gray-600">
                                    You subscribed for {{ $subscription->quantity }} unit(s) of {{ $subscription->commodity->name }}.
                                </p>
                            </div>
                        </div>

                        <!-- Status Update Event (if not pending) -->
                        @if($subscription->status !== 'pending')
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 h-full w-0.5 bg-gray-200"></div>
                            <div class="absolute top-0 left-0 -ml-1.5 mt-1.5 h-3 w-3 rounded-full border-2
                                @if($subscription->status === 'approved') border-green-500
                                @else border-red-500 @endif bg-white"></div>
                            <div class="flex flex-col">
                                <h4 class="text-sm font-medium text-gray-900">
                                    Subscription {{ ucfirst($subscription->status) }}
                                </h4>
                                <time datetime="{{ $subscription->updated_at }}" class="text-xs text-gray-500">
                                    {{ $subscription->updated_at->format('M d, Y h:i A') }}
                                </time>
                                <p class="mt-1 text-sm text-gray-600">
                                    @if($subscription->status === 'approved')
                                    Your subscription was approved by the administrator.
                                    @else
                                    Your subscription was rejected by the administrator.
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
