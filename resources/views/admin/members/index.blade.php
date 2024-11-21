@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Member Management</h2>
            <div class="flex space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search members..." class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-purple-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th scope="col" class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No.</th>
                                <th scope="col" class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                            <tr class="bg-white">
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $member->member_image) }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $member->title }} {{ $member->firstname }} {{ $member->surname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $member->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4">
                                    <div class="text-sm text-gray-900">{{ $member->member_no }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4">
                                    <div class="text-sm text-gray-900">{{ $member->department->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $member->admin_sign === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $member->admin_sign === 'Yes' ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.members.show', $member) }}" class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($member->admin_sign === 'No')
                                        <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.members.suspend', $member) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white">
                                <td colspan="5" class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 text-center text-gray-500">
                                    No members found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
