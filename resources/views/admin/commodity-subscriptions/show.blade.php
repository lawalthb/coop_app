@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.commodity-subscriptions.index') }}" class="text-purple-600 hover:text-purple-900">
                <i class="fas fa-arrow-left mr-2"></i> Back to Subscriptions
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Subscription Details</h2>
                <span class="px-3 py-1 text-sm rounded-full
                    @if($subscription->status === 'approved') bg-green-100 text-green-800
                    @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Member Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Name:</span>
                                <p class="font-medium">{{ $subscription->user->surname }} {{ $subscription->user->firstname }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Member ID:</span>
                                <p class="font-medium">{{ $subscription->user->member_no }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Email:</span>
                                <p class="font-medium">{{ $subscription->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Commodity Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Commodity:</span>
                                <p class="font-medium">{{ $subscription->commodity->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Unit Price:</span>
                                <p class="font-medium">₦{{ number_format($subscription->commodity->price, 2) }}</p>
                            </div>
                            <div class="flex space-x-8">
                                <div>
                                    <span class="text-sm text-gray-500">Quantity:</span>
                                    <p class="font-medium">{{ $subscription->quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Total Amount:</span>
                                    <p class="font-medium">₦{{ number_format($subscription->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($subscription->reason)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Reason for Subscription</h3>
                    <p class="text-gray-700">{{ $subscription->reason }}</p>
                </div>
                @endif

                @if($subscription->admin_notes)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Admin Notes</h3>
                    <p class="text-gray-700">{{ $subscription->admin_notes }}</p>
                </div>
                @endif

                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Timeline</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-gray-400"></i>
                            <span>Submitted on {{ $subscription->created_at->format('M d, Y \a\t h:i A') }}</span>
