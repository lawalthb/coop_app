@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Shares Report</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.shares.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <a href="{{ route('admin.reports.shares.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
                <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('admin.reports.shares') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Share Type</label>
        <select name="share_type_id" class="w-full rounded-lg border-gray-300">
            <option value="">All Types</option>
            @foreach($shareTypes as $type)
            <option value="{{ $type->id }}" {{ request('share_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
        <select name="month_id" class="w-full rounded-lg border-gray-300">
            <option value="">All Months</option>
            @foreach($months as $month)
            <option value="{{ $month->id }}" {{ request('month_id') == $month->id ? 'selected' : '' }}>
                {{ $month->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
        <select name="year_id" class="w-full rounded-lg border-gray-300">
            <option value="">All Years</option>
            @foreach($years as $year)
            <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                {{ $year->year }}
            </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
            class="w-full rounded-lg border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}"
            class="w-full rounded-lg border-gray-300">
    </div>
    <div class="flex items-end">
        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 w-full">
            Apply Filters
        </button>
    </div>
</form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Share Capital</h3>
                <p class="text-3xl font-bold text-purple-600">₦{{ number_format($totalAmount, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Approved & Not Yet</h3>
                <p class="text-3xl font-bold text-blue-600">{{$totalApproved }} & {{$totalNotyet }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Shareholders</h3>
                <p class="text-3xl font-bold text-green-600">{{$totalShareholders  }}</p>
            </div>
        </div>

        <!-- Shares Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($shares as $share)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->certificate_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $share->user->surname }} {{ $share->user->firstname }}</td>
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
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $shares->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
