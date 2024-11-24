@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Withdrawal History</h2>
        <a href="{{ route('member.withdrawals.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            New Withdrawal Request
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Savings Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $withdrawal->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $withdrawal->reference }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $withdrawal->savingType->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ₦{{ number_format($withdrawal->amount, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm">{{ $withdrawal->bank_name }}</p>
                            <p class="text-sm">{{ $withdrawal->account_number }}</p>
                            <p class="text-sm text-gray-500">{{ $withdrawal->account_name }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($withdrawal->status === 'completed') bg-green-100 text-green-800
                                @elseif($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No withdrawal requests found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
