@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Share Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Share <p class="text-3xl font-bold text-purple-600">₦{{ number_format($total_shares->sum('amount_paid'), 2) }}</p>

        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Share Units</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($total_shares->sum('number_of_units')) }} Units</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <a href="{{ route('member.shares.create') }}" class="block text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-plus mr-2"></i>Purchase New Shares
            </a>
        </div>
    </div>

    <!-- Share History -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Share Purchase History</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shares as $share)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->certificate_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->shareType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($share->number_of_units) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($share->amount_paid, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $share->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($share->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($share->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No share purchases found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
