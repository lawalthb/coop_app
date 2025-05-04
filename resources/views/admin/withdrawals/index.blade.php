@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Member Withdrawals</h1>
            <a href="{{ route('admin.withdrawals.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-plus mr-2"></i>Record New Withdrawal
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
            <button id="filter-toggle" class="w-full px-6 py-4 bg-gray-50 text-left flex justify-between items-center border-b">
                <div class="flex items-center">
                    <i class="fas fa-filter text-purple-600 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Filter Options</h2>
                </div>
                <div class="flex items-center text-gray-500">
                    <span id="filter-count" class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                        {{ array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])) ? count(array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id']))) : '0' }} active
                    </span>
                    <i id="filter-icon" class="fas fa-chevron-down transition-transform duration-300"></i>
                </div>
            </button>

            <div id="filter-panel" class="p-6 {{ array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])) ? '' : 'hidden' }}">
                <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select name="user_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->surname }} {{ $member->firstname }}
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Saving Type</label>
                        <select name="saving_type_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Types</option>
                            @foreach($savingTypes as $type)
                            <option value="{{ $type->id }}" {{ request('saving_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
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
                    <div class="flex items-end space-x-2 md:col-span-5">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex-1 flex items-center justify-center border border-purple-600">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.withdrawals.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center border border-gray-300">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])))
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                    <i class="fas fa-filter"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Active Filters:</h3>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if(request('user_id'))
                            @php $member = $members->firstWhere('id', request('user_id')); @endphp
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Member: {{ $member ? $member->surname . ' ' . $member->firstname : 'Unknown' }}
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Status: {{ ucfirst(request('status')) }}
                            </span>
                        @endif

                        @if(request('saving_type_id'))
                            @php $savingType = $savingTypes->firstWhere('id', request('saving_type_id')); @endphp
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Saving Type: {{ $savingType ? $savingType->name : 'Unknown' }}
                            </span>
                        @endif

                        @if(request('month_id'))
                            @php $month = $months->firstWhere('id', request('month_id')); @endphp
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Month: {{ $month ? $month->name : 'Unknown' }}
                            </span>
                        @endif

                        @if(request('year_id'))
                            @php $year = $years->firstWhere('id', request('year_id')); @endphp
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Year: {{ $year ? $year->year : 'Unknown' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div class="w-full">
                        <h3 class="text-lg font-normal text-gray-700 mb-1">Total Withdrawals</h3>
                        <p class="text-xl font-normal text-purple-600 truncate">₦{{ number_format($filteredTotalWithdrawals, 2) }}</p>
                        @if(array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])))
                            <p class="text-xs text-gray-500">Overall: ₦{{ number_format($totalWithdrawals, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="w-full">
                        <h3 class="text-lg font-normal text-gray-700 mb-1">Pending Withdrawals</h3>
                        <p class="text-xl font-normal text-yellow-600 truncate">₦{{ number_format($filteredPendingWithdrawals, 2) }}</p>
                        @if(array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])))
                            <p class="text-xs text-gray-500">Overall: ₦{{ number_format($pendingWithdrawals, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="w-full">
                        <h3 class="text-lg font-normal text-gray-700 mb-1">Approved Withdrawals</h3>
                        <p class="text-xl font-normal text-green-600 truncate">₦{{ number_format($filteredApprovedWithdrawals, 2) }}</p>
                        @if(array_filter(request()->only(['status', 'saving_type_id', 'month_id', 'year_id', 'user_id'])))
                            <p class="text-xs text-gray-500">Overall: ₦{{ number_format($approvedWithdrawals, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawals Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Withdrawal Requests</h2>
                <p class="text-sm text-gray-600">Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() ?? 0 }} withdrawals</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saving Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $withdrawal->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if(isset($withdrawal->user->member_image))
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $withdrawal->user->member_image) }}" alt="{{ $withdrawal->user->surname }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->surname }} {{ $withdrawal->user->firstname }}</div>
                                        <div class="text-xs text-gray-500">{{ $withdrawal->user->member_no ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    {{ $withdrawal->savingType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">₦{{ number_format($withdrawal->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $withdrawal->bank_name }}</div>
                                <div class="text-xs text-gray-500">{{ $withdrawal->account_number }}</div>
                                <div class="text-xs text-gray-500">{{ $withdrawal->account_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($withdrawal->status === 'pending')
                                <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm('Are you sure you want to approve this withdrawal?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <button type="button" class="text-red-600 hover:text-red-900"
                                        onclick="document.getElementById('reject-modal-{{ $withdrawal->id }}').classList.remove('hidden')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </td>
                        </tr>

                                             <!-- Reject Modal -->
                        @if($withdrawal->status === 'pending')
                        <div id="reject-modal-{{ $withdrawal->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Withdrawal Request</h3>
                                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                    @csrf
                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                                style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                                onclick="document.getElementById('reject-modal-{{ $withdrawal->id }}').classList.add('hidden')">
                                            Cancel
                                        </button>
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                            Confirm Rejection
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No withdrawal requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $withdrawals->withQueryString()->links() }}
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

        // Remove the date range validation code since we're using dropdowns now
    });
</script>
@endsection
