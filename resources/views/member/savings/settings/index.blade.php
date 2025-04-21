@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Monthly Savings Settings</h1>
        <a href="{{ route('member.savings.settings.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            <i class="fas fa-plus mr-2"></i>Set New Monthly Amount
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

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                            <div class="text-sm font-medium text-gray-900">{{ $setting->savingType->name }}</div>
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
                            <a href="{{ route('member.savings.settings.edit', $setting) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('member.savings.settings.destroy', $setting) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this setting?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif

                            @if($setting->status === 'rejected' && $setting->admin_notes)
                            <button type="button" class="text-gray-600 hover:text-gray-900"
                                    onclick="document.getElementById('notes-modal-{{ $setting->id }}').classList.remove('hidden')">
                                <i class="fas fa-info-circle"></i>
                            </button>

                            <!-- Notes Modal -->
                            <div id="notes-modal-{{ $setting->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h3>
                                    <p class="text-gray-700">{{ $setting->admin_notes }}</p>
                                    <div class="mt-4 flex justify-end">
                                                                              <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                                onclick="document.getElementById('notes-modal-{{ $setting->id }}').classList.add('hidden')">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No monthly savings settings found. <a href="{{ route('member.savings.settings.create') }}" class="text-purple-600 hover:text-purple-900">Create one now</a>.
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

    {{-- <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">About Monthly Savings Settings</h2>
        <div class="text-gray-700 space-y-4">
            <p>
                <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                Monthly savings settings allow you to specify how much you want to save each month.
            </p>
            <p>
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <strong>Approved</strong> settings will be used by the cooperative to deduct the specified amount from your salary.
            </p>
            <p>
                <i class="fas fa-clock text-yellow-600 mr-2"></i>
                <strong>Pending</strong> settings are waiting for admin approval.
            </p>
            <p>
                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                <strong>Rejected</strong> settings will not be applied. You can view the admin's notes to understand why.
            </p>
        </div>
    </div> --}}
</div>
@endsection
