@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Transaction Details</h2>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reference</label>
                        <p class="mt-1 text-lg">{{ $transaction->reference }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <p class="mt-1 text-lg">{{ $transaction->created_at->format('M d, Y H:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </p>
                    </div>

                    <div>

                        @if($transaction->type === 'savings')
                        <label class="block text-sm font-medium text-gray-700">Credit Amount</label>
                        <p class="mt-1 text-lg font-semibold text-green-600">₦{{ number_format($transaction->credit_amount, 2) }}</p>
                        @else
                        <label class="block text-sm font-medium text-gray-700">Debit Amount</label>
                        <p class="mt-1 text-lg font-semibold text-red-600">₦{{ number_format($transaction->debit_amount, 2) }}</p>
                        @endif
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1">{{ $transaction->description }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('member.transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
