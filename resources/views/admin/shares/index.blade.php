@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Shares Management</h1>
            <a href="{{ route('admin.shares.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-plus mr-2"></i>New Share Purchase
            </a>
        </div>

        <!-- Total Shares Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-1">Total Share Value</h2>
                    <p class="text-3xl font-bold text-purple-600">₦{{ number_format($totalShares, 2) }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(request('share_type_id') || request('month_id') || request('year_id') || request('status'))
                            Filtered total based on your criteria
                        @else
                            Total value of all shares
                        @endif
                    </p>
                </div>
                <button id="toggleFilters" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-filter mr-2"></i>Show Filters
                </button>
            </div>
        </div>

        <!-- Collapsible Filter and Import Section -->
        <div id="filtersSection" class="bg-white rounded-xl shadow-lg overflow-hidden mb-6" style="display: none;">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filter Shares</h3>
                    <button id="toggleImport" class="text-purple-600 hover:text-purple-800 focus:outline-none">
                        <i class="fas fa-file-import mr-2"></i>Import Shares
                    </button>
                </div>

                <!-- Import Form (Initially Hidden) -->
                <div id="importSection" class="bg-gray-50 rounded-lg p-4 mb-4" style="display: none;">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Import Shares</h3>
                    <form action="{{ route('admin.shares.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                        @csrf
                        <div class="flex-1">
                            <input type="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Import Shares
                        </button>
                    </form>
                    <div class="mt-2 text-sm text-gray-600">
                        File must contain: email, amount, month_id, year_id
                    </div>
                </div>

                <!-- Filter Form -->
                <form action="{{ route('admin.shares.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share Type</label>
                        <select name="share_type_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">All Types</option>
                            @foreach($shareTypes as $type)
                            <option value="{{ $type->id }}" {{ request('share_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                        <select name="month_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
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
                        <select name="year_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="md:col-span-4 flex items-center space-x-4">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>

                        @if(request('share_type_id') || request('month_id') || request('year_id') || request('status'))
                        <a href="{{ route('admin.shares.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-times mr-2"></i>Clear Filters
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posted By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($shares as $share)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->certificate_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->user->surname }} {{ $share->user->firstname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->shareType->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($share->amount_paid, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->month->name }} {{ $share->year->year }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $share->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($share->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($share->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->postedBy->surname }} {{ $share->postedBy->firstname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $share->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.shares.show', $share) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($share->status === 'pending')
                                    <form action="{{ route('admin.shares.approve', $share) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to approve this share purchase?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.shares.reject', $share) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this share purchase?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.shares.destroy', $share) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this share purchase?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">No share purchases found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $shares->appends(request()->query())->links() }}
            </div>
        </div>
     </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle filters section
        const toggleFiltersBtn = document.getElementById('toggleFilters');
        const filtersSection = document.getElementById('filtersSection');

        toggleFiltersBtn.addEventListener('click', function() {
            const isVisible = filtersSection.style.display !== 'none';
            filtersSection.style.display = isVisible ? 'none' : 'block';
            toggleFiltersBtn.innerHTML = isVisible
                ? '<i class="fas fa-filter mr-2"></i>Show Filters'
                : '<i class="fas fa-filter mr-2"></i>Hide Filters';
        });

        // Toggle import section
        const toggleImportBtn = document.getElementById('toggleImport');
        const importSection = document.getElementById('importSection');

        toggleImportBtn.addEventListener('click', function() {
            const isVisible = importSection.style.display !== 'none';
            importSection.style.display = isVisible ? 'none' : 'block';
        });

        // If there are active filters, show the filters section by default
        @if(request('share_type_id') || request('month_id') || request('year_id') || request('status'))
            filtersSection.style.display = 'block';
            toggleFiltersBtn.innerHTML = '<i class="fas fa-filter mr-2"></i>Hide Filters';
        @endif
    });
</script>
@endsection
