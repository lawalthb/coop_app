@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Savings Summary Report</h1>
                <p class="text-gray-600">Overview of all members' savings, withdrawals and balances</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.savings') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Savings Report
                </a>
                <a href="{{ route('admin.reports.savings.summary.excel', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
            </div>
        </div>

        <!-- Overall Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-piggy-bank text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Saved</h3>
                        <p class="text-2xl font-bold text-green-600">₦{{ number_format($overallTotals['total_saved'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Withdrawn</h3>
                        <p class="text-2xl font-bold text-red-600">₦{{ number_format($overallTotals['total_withdrawn'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Balance</h3>
                        <p class="text-2xl font-bold text-blue-600">₦{{ number_format($overallTotals['total_balance'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Active Savers</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $overallTotals['active_savers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('admin.reports.savings.summary') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Member</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Member No, Name..."
                            class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex-1 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.reports.savings.summary') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members Summary Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Members Savings Summary</h2>
                <p class="text-sm text-gray-600">Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} of {{ $members->total() ?? 0 }} members</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Saved</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Withdrawn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $index => $member)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $member->member_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($member->member_image)
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $member->member_image) }}" alt="{{ $member->surname }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->title }} {{ $member->surname }} {{ $member->firstname }}</div>
                                        {{-- <div class="text-xs text-gray-500">{{ $member->email }}</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                ₦{{ number_format($member->total_saved, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                ₦{{ number_format($member->total_withdrawn, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $member->balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                {{ number_format($member->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($member->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.members.show', $member->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.financial-summary.member', $member->id) }}" class="text-purple-600 hover:text-purple-900" title="Financial Summary">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                                                @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No members found matching the selected criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-gray-900">Totals:</td>
                            <td class="px-6 py-4 text-green-600">₦{{ number_format($overallTotals['total_saved'], 2) }}</td>
                            <td class="px-6 py-4 text-red-600">₦{{ number_format($overallTotals['total_withdrawn'], 2) }}</td>
                            <td class="px-6 py-4 {{ $overallTotals['total_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                ₦{{ number_format($overallTotals['total_balance'], 2) }}
                            </td>
                            <td colspan="2" class="px-6 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $members->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .bg-purple-50 { background-color: white !important; }
        .shadow-lg { box-shadow: none !important; }
        .rounded-xl { border-radius: 0 !important; }
        .bg-white { background-color: white !important; }

        button, a.bg-gray-600, button.bg-purple-600,
        form, .pagination, .flex.space-x-3 {
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
            content: "OGITECH COOPERATIVE SOCIETY - SAVINGS SUMMARY REPORT";
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
