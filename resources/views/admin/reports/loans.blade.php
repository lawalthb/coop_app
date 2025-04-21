@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Loans Report</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.loans.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <a href="{{ route('admin.reports.loans.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
                <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
            </div>
        </div>

        <!-- Collapsible Filters -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
            <button id="filter-toggle" class="w-full px-6 py-4 bg-gray-50 text-left flex justify-between items-center border-b">
                <div class="flex items-center">
                    <i class="fas fa-filter text-purple-600 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Filter Options</h2>
                </div>
                <div class="flex items-center text-gray-500">
                    <span id="filter-count" class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                        {{ array_filter(request()->only(['loan_type_id', 'status', 'month_id', 'year_id', 'date_from', 'date_to'])) ? count(array_filter(request()->only(['loan_type_id', 'status', 'month_id', 'year_id', 'date_from', 'date_to']))) : '0' }} active
                    </span>
                    <i id="filter-icon" class="fas fa-chevron-down transition-transform duration-300"></i>
                </div>
            </button>

            <div id="filter-panel" class="p-6 {{ array_filter(request()->only(['loan_type_id', 'status', 'month_id', 'year_id', 'date_from', 'date_to'])) ? '' : 'hidden' }}">
                <form action="{{ route('admin.reports.loans') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loan Type</label>
                        <select name="loan_type_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Types</option>
                            @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}" {{ request('loan_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                        <select name="month_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
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
                        <select name="year_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
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
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>
                    <div class="flex items-end space-x-2 md:col-span-3">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex-1 flex items-center justify-center border border-purple-600">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.reports.loans') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center border border-gray-300">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Loans</h3>
                        <p class="text-3xl font-bold text-purple-600">₦{{ number_format($totalLoans, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-hand-holding-usd text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Active Loans</h3>
                        <p class="text-3xl font-bold text-blue-600">₦{{ number_format($activeLoans, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Repayments</h3>
                        <p class="text-3xl font-bold text-green-600">₦{{ number_format($totalRepayments, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Outstanding Balance</h3>
                        <p class="text-3xl font-bold text-red-600">₦{{ number_format($outstandingBalance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loans Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Loan Transactions</h2>
                <p class="text-sm text-gray-600">Showing {{ $loans->firstItem() ?? 0 }} to {{ $loans->lastItem() ?? 0 }} of {{ $loans->total() ?? 0 }} loans</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                                             @foreach($loans as $loan)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if(isset($loan->user->member_image))
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $loan->user->member_image) }}" alt="{{ $loan->user->surname }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $loan->user->surname }} {{ $loan->user->firstname }}</div>
                                        <div class="text-xs text-gray-500">{{ $loan->user->member_no ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    {{ $loan->loanType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">₦{{ number_format($loan->amount, 2) }}</div>
                                <div class="text-xs text-gray-500">
                                    Interest: ₦{{ number_format($loan->interest_amount, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $loan->duration }} months</div>
                                <div class="text-xs text-gray-500">
                                    {{ $loan->start_date ? $loan->start_date->format('M Y') : 'N/A' }} -
                                    {{ $loan->end_date ? $loan->end_date->format('M Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $loans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle filter panel
        const filterToggle = document.getElementById('filter-toggle');
        const filterPanel = document.getElementById('filter-panel');
        const filterIcon = document.getElementById('filter-icon');

        filterToggle.addEventListener('click', function() {
            filterPanel.classList.toggle('hidden');
            filterIcon.classList.toggle('transform');
            filterIcon.classList.toggle('rotate-180');
        });

        // Date range validation
        const dateFrom = document.querySelector('input[name="date_from"]');
        const dateTo = document.querySelector('input[name="date_to"]');

        if (dateFrom && dateTo) {
            dateFrom.addEventListener('change', function() {
                dateTo.min = this.value;
                if (dateTo.value && dateTo.value < this.value) {
                    dateTo.value = this.value;
                }
            });

            dateTo.addEventListener('change', function() {
                dateFrom.max = this.value;
                if (dateFrom.value && dateFrom.value > this.value) {
                    dateFrom.value = this.value;
                }
            });
        }
    });
</script>

<style>
    @media print {
        .bg-purple-50 { background-color: white !important; }
        .shadow-lg { box-shadow: none !important; }
        .rounded-xl { border-radius: 0 !important; }
        .bg-white { background-color: white !important; }

        button, a.bg-green-600, a.bg-red-600, button.bg-purple-600,
        form button[type="submit"], .pagination, .flex.space-x-3,
        #filter-toggle, #filter-panel {
            display: none !important;
        }

        .min-h-screen { min-height: auto !important; }

        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }

        .transition-transform { transform: none !important; }

        /* Add a title for print version */
        .max-w-7xl::before {
            content: "OGITECH COOPERATIVE SOCIETY - LOANS REPORT";
            display: block;
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Print date */
        .max-w-7xl::after {
            content: "Printed on: {{ date('F d, Y h:i A') }}";
            display: block;
            font-size: 10pt;
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
    }
</style>
@endsection

