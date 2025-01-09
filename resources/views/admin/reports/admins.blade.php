@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Administrators Report</h1>
            <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-print mr-2"></i>Print Report
            </button>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Administrators</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $admins->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Approved Shares</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $admins->sum('approved_shares_count') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Approved Loans</h3>
                <p class="text-3xl font-bold text-green-600">{{ $admins->sum('approved_loans_count') }}</p>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admin ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Join Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved Loans</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($admins as $admin)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->member_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->surname }} {{ $admin->firstname }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->role->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->approved_shares_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $admin->approved_loans_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
