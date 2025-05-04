@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Loan Management</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.loans.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-plus mr-2"></i>New Loan Application
                </a>
                <a href="{{ route('admin.loans.import') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-file-import mr-2"></i>Import Loans
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Loan Amount Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Loan Amount</h3>
                        <p class="text-2xl font-bold text-gray-800">₦{{ number_format($totalLoanAmount, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('reference') || request('status'))
                                Filtered total based on your criteria
                            @else
                                Total of all loans
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Outstanding Amount Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-hand-holding-usd text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Outstanding Balance</h3>
                        <p class="text-2xl font-bold text-gray-800">₦{{ number_format($totalOutstandingAmount, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(request('reference') || request('status'))
                                Filtered outstanding amount
                            @else
                                Total outstanding balance
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <form action="{{ route('admin.loans.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-auto">
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
                        Filter by Loan Reference
                    </label>
                    <select name="reference" id="reference"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All Loans</option>
                        @foreach($loanReferences as $loanRef)
                            <option value="{{ $loanRef->reference }}" {{ request('reference') == $loanRef->reference ? 'selected' : '' }}>
                                {{ $loanRef->surname }} {{ $loanRef->firstname }} - {{ $loanRef->loan_type_name }} ({{ $loanRef->reference }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Filter by Status
                    </label>
                    <select name="status" id="status"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                            @if(isset($statusCounts['pending']))
                                <span class="text-yellow-600">({{ $statusCounts['pending'] }})</span>
                            @endif
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Approved
                            @if(isset($statusCounts['approved']))
                                <span class="text-green-600">({{ $statusCounts['approved'] }})</span>
                            @endif
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Rejected
                            @if(isset($statusCounts['rejected']))
                                <span class="text-red-600">({{ $statusCounts['rejected'] }})</span>
                            @endif
                        </option>
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    @if(request('reference') || request('status'))
                        <a href="{{ route('admin.loans.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($loans as $loan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $loan->user->surname }} {{ $loan->user->firstname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->loanType->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($loan->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->duration }} months</td>
                            <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($loan->monthly_payment, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($loan->status === 'pending')
                                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to approve this loan?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this loan?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No loan applications found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
