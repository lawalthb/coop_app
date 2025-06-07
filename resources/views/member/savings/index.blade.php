@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Monthly Summary Button -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('member.savings.monthly-summary') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            <i class="fas fa-table mr-2"></i>Monthly Summary
        </a>

                &nbsp; &nbsp;
         <a href="{{ route('member.savings.settings.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        <i class="fas fa-cog mr-2"></i>Manage Monthly Savings
    </a>
    </div>

    <!-- Savings Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($savingTypes as $type)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ $type->name }}</h3>
            <p class="text-3xl font-bold text-purple-600">₦{{ number_format($savingsData[$type->id], 2) }}</p>
        </div>
        @endforeach
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Current Month Total</h3>
            <p class="text-3xl font-bold text-green-600">₦{{ number_format($currentMonthTotal, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Savingd Balance</h3>
            <p class="text-3xl font-bold text-green-600">₦{{ number_format($savingsBalance, 2) }}</p>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select name="saving_type_id" class="rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    <option value="">All Types</option>
                    @foreach($savingTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="start_date" class="rounded-lg border-gray-300" placeholder="Start Date" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                <input type="date" name="end_date" class="rounded-lg border-gray-300" placeholder="End Date" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ $transaction->savingType->name }}</td>
                        <td class="px-6 py-4">₦{{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
