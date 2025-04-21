@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Monthly Savings Settings</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <form action="{{ route('admin.savings.settings.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Filter
                        </button>
                        <a href="{{ route('admin.savings.settings.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saving Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($settings as $setting)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $setting->user->surname }} {{ $setting->user->firstname }}
                                    </div>
                                    <div class="text-sm text-gray-500 ml-1">({{ $setting->user->member_no }})</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $setting->savingType->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $setting->month->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $setting->year->year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₦{{ number_format($setting->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($setting->status === 'approved') bg-green-100 text-green-800
                                    @elseif($setting->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($setting->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $setting->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($setting->status === 'pending')
                                <button type="button" class="text-green-600 hover:text-green-900 mr-3"
                                        onclick="document.getElementById('approve-modal-{{ $setting->id }}').classList.remove('hidden')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="text-red-600 hover:text-red-900"
                                        onclick="document.getElementById('reject-modal-{{ $setting->id }}').classList.remove('hidden')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Approve Modal -->
                        <div id="approve-modal-{{ $setting->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Monthly Savings Setting</h3>
                                <p class="text-gray-700 mb-4">
                                    Are you sure you want to approve this monthly savings setting of
                                    <span class="font-semibold">₦{{ number_format($setting->amount, 2) }}</span>
                                    for {{ $setting->user->surname }} {{ $setting->user->firstname }}?
                                </p>
                                <div class="mt-4 flex justify-end space-x-3">
                                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                            onclick="document.getElementById('approve-modal-{{ $setting->id }}').classList.add('hidden')">
                                        Cancel
                                    </button>
                                    <form action="{{ route('admin.savings.settings.approve', $setting) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                                            Confirm Approval
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="reject-modal-{{ $setting->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Monthly Savings Setting</h3>
                                <form action="{{ route('admin.savings.settings.reject', $setting) }}" method="POST">
                                    @csrf
                                    <div>
                                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                        <textarea name="admin_notes" id="admin_notes" rows="3" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                                style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                                onclick="document.getElementById('reject-modal-{{ $setting->id }}').classList.add('hidden')">
                                            Cancel
                                        </button>
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                            Confirm Rejection
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No monthly savings settings found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $settings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
