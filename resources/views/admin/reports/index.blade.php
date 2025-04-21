@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
            <div class="flex space-x-4">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-print mr-2"></i> Print Reports
                </button>
            </div>
        </div>

        <!-- Main Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Members Report -->
            <a href="{{ route('admin.reports.members') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-purple-600 mb-4">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Members Report</h3>
                </div>
                <p class="text-gray-600">View comprehensive member statistics and activities</p>
                <div class="mt-4 flex justify-end text-purple-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Financial Reports -->
            <a href="{{ route('admin.reports.savings') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-indigo-600 mb-4">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <i class="fas fa-piggy-bank text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Savings Report</h3>
                </div>
                <p class="text-gray-600">Analyze member savings and contributions</p>
                <div class="mt-4 flex justify-end text-indigo-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Shares Report -->
            <a href="{{ route('admin.reports.shares') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-yellow-600 mb-4">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Shares Report</h3>
                </div>
                <p class="text-gray-600">Review share distributions and purchases</p>
                <div class="mt-4 flex justify-end text-yellow-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Loans Report -->
            <a href="{{ route('admin.reports.loans') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-red-600 mb-4">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Loans Report</h3>
                </div>
                <p class="text-gray-600">Track loan disbursements and repayments</p>
                <div class="mt-4 flex justify-end text-red-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Transactions Report -->
            <a href="{{ route('admin.reports.transactions') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-teal-600 mb-4">
                    <div class="p-3 bg-teal-100 rounded-lg">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Transactions Report</h3>
                </div>
                <p class="text-gray-600">View all financial transactions and history</p>
                <div class="mt-4 flex justify-end text-teal-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Transaction Summary -->
            <a href="{{ route('admin.reports.transaction-summary') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center text-purple-600 mb-4">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold ml-4">Transaction Summary</h3>
                </div>
                <p class="text-gray-600">View and analyze transactions by type, member, and period</p>
                <div class="mt-4 flex justify-end text-purple-600">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection

