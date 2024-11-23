@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Savings Entry Details</h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.savings.edit', $saving) }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.savings') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Reference Information -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800 mb-2">Reference Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Reference Number:</span>
                            <span class="font-medium">{{ $saving->reference }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($saving->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Member Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Member Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ $saving->user->surname }} {{ $saving->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Staff Number:</span>
                            <span class="font-medium">{{ $saving->user->staff_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Savings Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Savings Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">₦{{ number_format($saving->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Saving Type:</span>
                            <span class="font-medium">{{ $saving->savingType->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Period:</span>
                            <span class="font-medium">{{ $saving->month->name }} {{ $saving->year->year }}</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Posted By:</span>
                            <span class="font-medium">{{ $saving->postedBy->surname }} {{ $saving->postedBy->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Posted Date:</span>
                            <span class="font-medium">{{ $saving->created_at->format('M d, Y H:i A') }}</span>
                        </div>
                    </div>
                </div>

                @if($saving->remark)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Remarks</h3>
                    <p class="text-gray-700">{{ $saving->remark }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
