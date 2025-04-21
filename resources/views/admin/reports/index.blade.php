@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Reports Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Members Report -->
            <a href="{{ route('admin.reports.members') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-purple-600 mb-4">
                    <i class="fas fa-users text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Members Report</h3>
                </div>
                <p class="text-gray-600">View comprehensive member statistics and activities</p>
            </a>

            <!-- Admins Report -->
            <a href="{{ route('admin.reports.admins') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-blue-600 mb-4">
                    <i class="fas fa-user-shield text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Admins Report</h3>
                </div>
                <p class="text-gray-600">Track admin activities and permissions</p>
            </a>

            <!-- Entrance Fees Report -->
            <a href="{{ route('admin.reports.entrance-fees') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-green-600 mb-4">
                    <i class="fas fa-ticket-alt text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Entrance Fees Report</h3>
                </div>
                <p class="text-gray-600">Monitor entrance fee collections and status</p>
            </a>

            <!-- Savings Report -->
            <a href="{{ route('admin.reports.savings') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-indigo-600 mb-4">
                    <i class="fas fa-piggy-bank text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Savings Report</h3>
                </div>
                <p class="text-gray-600">Analyze member savings and contributions</p>
            </a>

            <!-- Shares Report -->
            <a href="{{ route('admin.reports.shares') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-yellow-600 mb-4">
                    <i class="fas fa-chart-pie text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Shares Report</h3>
                </div>
                <p class="text-gray-600">Review share distributions and purchases</p>
            </a>

            <!-- Loans Report -->
            <a href="{{ route('admin.reports.loans') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-red-600 mb-4">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Loans Report</h3>
                </div>
                <p class="text-gray-600">Track loan disbursements and repayments</p>
            </a>

            <!-- Transactions Report -->
            <a href="{{ route('admin.reports.transactions') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center text-teal-600 mb-4">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                    <h3 class="text-lg font-semibold ml-3">Transactions Report</h3>
                </div>
                <p class="text-gray-600">View all financial transactions and history</p>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="p-5 bg-purple-600">
        <h3 class="text-lg font-semibold text-white">Transaction Summary Report</h3>
    </div>
    <div class="p-5">
        <p class="text-gray-600 mb-4">View and analyze transactions by type, member, and period.</p>
        <a href="{{ route('admin.reports.transaction-summary') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            <i class="fas fa-chart-bar mr-2"></i>View Report
        </a>
    </div>
</div>

</div>
@endsection

