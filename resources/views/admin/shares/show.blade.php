@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Share Details</h2>
                <a href="{{ route('admin.shares.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Certificate Information -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800 mb-2">Certificate Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Certificate Number:</span>
                            <span class="font-medium">{{ $share->certificate_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($share->status) }}
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
                            <span class="font-medium">{{ $share->user->surname }} {{ $share->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Staff Number:</span>
                            <span class="font-medium">{{ $share->user->staff_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Share Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Share Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Number of Shares:</span>
                            <span class="font-medium">{{ number_format($share->number_of_shares) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount per Share:</span>
                            <span class="font-medium">₦{{ number_format($share->amount_per_share, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium">₦{{ number_format($share->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Posted By:</span>
                            <span class="font-medium">{{ $share->postedBy->surname }} {{ $share->postedBy->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Posted Date:</span>
                            <span class="font-medium">{{ $share->created_at->format('M d, Y H:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Share Transactions -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Share Transactions</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{--
                                @foreach($share->transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($transaction->transaction_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($transaction->number_of_shares) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($transaction->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->reference }}</td>
                                </tr>
                                @endforeach
                                --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
