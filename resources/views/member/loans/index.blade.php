@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Active Loans -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Active Loans</h1>
            <div class="flex space-x-4">
                <a href="{{ route('member.loan-calculator') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-calculator mr-2"></i>Loan Calculator
                </a>
                <a href="{{ route('member.loans.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i> Apply for Loan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($activeLoans->count() > 0)
            @foreach($activeLoans as $loan)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $loan->loanType->name }}</h3>
                        <p class="text-sm text-gray-500">Approved: {{ $loan->approved_at?->format('M d, Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
                <div class="space-y-2">
                    <p class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-semibold">₦{{ number_format($loan->amount, 2) }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span>{{ $loan->duration }} months</span>
                    </p>
                 <p class="flex justify-between">
    <span class="text-gray-600">Balance:</span>
    <span class="font-semibold">₦{{ number_format($loan->balance, 2) }}</span>
</p>
                </div>
                <a href="{{ route('member.loans.show', $loan) }}" class="mt-4 text-purple-600 hover:text-purple-700 text-sm font-medium">
                    View Details →
                </a>
            </div>
            @endforeach
            @else
            <div class="col-span-full bg-gray-50 rounded-xl p-8 text-center">
                <p class="text-gray-600">No active loans</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Loan History -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Loan History</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($loanHistory as $loan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $loan->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $loan->loanType->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ₦{{ number_format($loan->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $loan->duration }} months
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($loan->status === 'active') bg-green-100 text-green-800
                                @elseif($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($loan->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-green-100 text-white-800 @endif">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('member.loans.show', $loan) }}" class="text-purple-600 hover:text-purple-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
