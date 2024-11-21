@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Member Management</h2>
            <div class="flex space-x-4">
                <div class="relative">
                    <form action="{{ route('admin.members') }}" method="GET" class="relative">
                        <input type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Search members..."
                            class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-purple-500">
                        <button type="submit" class="absolute left-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                        @if($search)
                        <a href="{{ route('admin.members') }}" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden md:rounded-lg">
                    @if(session('warning'))
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                        {{ session('warning') }}
                    </div>
                    @endif

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
                                        <a title="Click to View Details" href="{{ route('admin.members.show', $member) }}" class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.members.edit', $member) }}" class="text-blue-600 hover:text-blue-900" title="Edit Member">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($member->admin_sign === 'No')
                                        <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button title="Click to Approve Member" type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($member->admin_sign === 'Yes')
                                        <form action="{{ route('admin.members.suspend', $member) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button title="Click to Suspend Member" type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if (auth()->user()->id !== $member->id)


                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Member">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
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
