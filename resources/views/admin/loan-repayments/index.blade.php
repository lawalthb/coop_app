@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Active Loans - Repayment Management</h2>
                    <div class="flex items-center space-x-4">
                        <div class="text-white text-sm">
                            Total Active Loans: {{ $loans->count() }}
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.loans.repayments.download-template') }}"
                               class="inline-flex items-center px-4 py-2 border border-white text-sm font-medium rounded-md text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Template
                            </a>
                            <a href="{{ route('admin.loans.repayments.upload') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Repayment
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('upload_errors') && count(session('upload_errors')) > 0)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <h4 class="font-semibold mb-2">Upload Errors:</h4>
                        <ul class="list-disc list-inside text-sm max-h-40 overflow-y-auto">
                            @foreach(session('upload_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($loans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Loan Details
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Member
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount Info
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duration & Progress
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($loans as $loan)
                                    <tr class="hover:bg-gray-50 {{ $loan->is_overdue ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900">{{ $loan->reference }}</div>
                                                <div class="text-gray-500">{{ $loan->loanType->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400">
                                                    Start: {{ $loan->start_date?->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    End: {{ $loan->end_date?->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900">{{ $loan->user->surname }} {{ $loan->user->firstname }} {{ $loan->user->othername }}</div>
                                                <div class="text-gray-500">{{ $loan->user->member_no ?? 'N/A' }}</div>
                                                <div class="text-gray-500">{{ $loan->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <div class="text-gray-900">
                                                    <span class="font-medium">Total:</span> ₦{{ number_format($loan->total_amount, 2) }}
                                                </div>
                                                <div class="text-green-600">
                                                    <span class="font-medium">Paid:</span> ₦{{ number_format($loan->amount_paid, 2) }}
                                                </div>
                                                <div class="text-red-600">
                                                    <span class="font-medium">Balance:</span> ₦{{ number_format($loan->balance, 2) }}
                                                </div>
                                                <div class="text-gray-500">
                                                    <span class="font-medium">Monthly:</span> ₦{{ number_format($loan->monthly_payment, 2) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-purple-600 h-2 rounded-full"
                                                             style="width: {{ $loan->duration > 0 ? ($loan->months_elapsed / $loan->duration) * 100 : 0 }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-gray-900">
                                                    <span class="font-medium">Total Duration:</span> {{ $loan->duration }} months
                                                </div>
                                                <div class="text-blue-600">
                                                    <span class="font-medium">Elapsed:</span> {{ number_format($loan->months_elapsed,2) }} months
                                                </div>
                                                <div class="text-orange-600">
                                                    <span class="font-medium">Remaining:</span> {{ number_format($loan->remaining_months,2) }} months
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $loan->is_overdue ? 'bg-red-100 text-red-800' :
                                                       ($loan->remaining_months <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ $loan->is_overdue ? 'Overdue' :
                                                       ($loan->remaining_months <= 3 ? 'Due Soon' : 'Active') }}
                                                </span>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ ucfirst($loan->status) }}
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $loan->repayments->count() }} payments made
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('admin.loans.repayments.create', $loan) }}"
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    Add Payment
                                                </a>
                                                <a href="{{ route('admin.loans.show', $loan) }}"
                                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    View Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Cards -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-800">Total Active Loans</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $loans->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-green-800">Total Outstanding</h3>
                            <p class="text-2xl font-bold text-green-900">₦{{ number_format($loans->sum('balance'), 2) }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-yellow-800">Due Soon (≤3 months)</h3>
                            <p class="text-2xl font-bold text-yellow-900">{{ $loans->where('remaining_months', '<=', 3)->where('remaining_months', '>', 0)->count() }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-red-800">Overdue Loans</h3>
                            <p class="text-2xl font-bold text-red-900">{{ $loans->where('is_overdue', true)->count() }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 text-lg">No active loans found</div>
                        <p class="text-gray-400 mt-2">All loans have been completed or there are no approved loans yet.</p>
                        <div class="mt-4 space-x-2">
                            <a href="{{ route('admin.loans.repayments.download-template') }}"
                               class="inline-flex items-center px-4 py-2 border border-purple-600 text-sm font-medium rounded-md text-purple-600 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Template
                            </a>
                            <a href="{{ route('admin.loans.repayments.upload') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Repayment
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
