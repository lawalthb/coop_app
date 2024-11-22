@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Entrance Fees Management</h1>
        <a href="{{ route('admin.entrance-fees.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            <i class="fas fa-plus mr-2"></i>Add New Fee
        </a>
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No entrance fees found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $entranceFees->links() }}
        </div>
    </div>
</div>
@endsection
