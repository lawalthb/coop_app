@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
     @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Entrance Fees Management</h1>
        <a href="{{ route('admin.entrance-fees.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            <i class="fas fa-plus mr-2"></i>Add New Fee
        </a>
    </div>

    <!-- Total Amount Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-normal text-gray-700 mb-1">Total Entrance Fees</h2>
                <p class="text-2xl font-normal text-purple-600">₦{{ number_format($totalAmount, 2) }}</p>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('admin.entrance-fees') }}" method="GET" class="flex items-center space-x-4">
                <div>
                    <label for="month_id" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select id="month_id" name="month_id" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Months</option>
                        @foreach($months as $month)
                            <option value="{{ $month->id }}" {{ request('month_id') == $month->id ? 'selected' : '' }}>
                                {{ $month->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year_id" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select id="year_id" name="year_id" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>

                    @if(request('month_id') || request('year_id'))
                        <a href="{{ route('admin.entrance-fees') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            <i class="fas fa-times mr-1"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="mb-6">
        <form action="{{ route('admin.entrance-fees.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
            @csrf
            <input type="file" name="file" class="form-input" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Import Fees
            </button>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month/Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($entranceFees as $fee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $fee->user->surname }} {{ $fee->user->firstname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ₦{{ number_format($fee->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $fee->month->name }} {{ $fee->year->year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $fee->postedBy->surname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $fee->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.entrance-fees.edit', $fee) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.entrance-fees.destroy', $fee) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No entrance fees found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $entranceFees->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
