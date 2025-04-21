@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Savings Report</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.savings.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <a href="{{ route('admin.reports.savings.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
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
                        {{ array_filter(request()->only(['member_id', 'saving_type', 'date_from', 'date_to'])) ? count(array_filter(request()->only(['member_id', 'saving_type', 'date_from', 'date_to']))) : '0' }} active
                    </span>
                    <i id="filter-icon" class="fas fa-chevron-down transition-transform duration-300"></i>
                </div>
            </button>

            <div id="filter-panel" class="p-6 {{ array_filter(request()->only(['member_id', 'saving_type', 'date_from', 'date_to'])) ? '' : 'hidden' }}">
                <form action="{{ route('admin.reports.savings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select name="member_id" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->title }}  {{ $member->surname }} {{ $member->firstname }} -  {{ $member->member_no }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Saving Type</label>
                        <select name="saving_type" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Types</option>
                            @foreach($savingTypes as $type)
                            <option value="{{ $type->id }}" {{ request('saving_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>

                    <div class="flex items-end space-x-2 md:col-span-4">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex-1 flex items-center justify-center border border-purple-600">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.reports.savings') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center border border-gray-300">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-piggy-bank text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Savings</h3>
                        <p class="text-3xl font-bold text-purple-600">₦{{ number_format($totalSavings, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Active Savers</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $activeSavers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Monthly Average</h3>
                        <p class="text-3xl font-bold text-blue-600">₦{{ number_format($monthlyAverage, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Savings Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Savings Transactions</h2>
                <p class="text-sm text-gray-600">Showing {{ $savings->firstItem() ?? 0 }} to {{ $savings->lastItem() ?? 0 }} of {{ $savings->total() ?? 0 }} transactions</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saving Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($savings as $saving)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $saving->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($saving->user->member_image)
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $saving->user->member_image) }}" alt="{{ $saving->user->surname }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $saving->user->surname }} {{ $saving->user->firstname }}</div>
                                        <div class="text-xs text-gray-500">{{ $saving->user->member_no }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    {{ $saving->savingType->name ?? 'Regular Savings' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">₦{{ number_format($saving->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $saving->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($saving->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.savings.show', $saving->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No savings transactions found matching the selected criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $savings->withQueryString()->links() }}
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
            content: "OGITECH COOPERATIVE SOCIETY - SAVINGS REPORT";
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
