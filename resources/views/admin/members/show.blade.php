@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Member Header -->
        <div class="bg-purple-600 px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-20 w-20 rounded-full border-4 border-white" src="{{ asset('storage/' . $member->member_image) }}" alt="">
                </div>
                <div class="ml-6 text-white">
                    <h2 class="text-2xl font-bold">{{ $member->title }} {{ $member->firstname }} {{ $member->othername }} {{ $member->surname }}</h2>
                    <p class="text-purple-200">Member No.: {{ $member->member_no }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="border-b px-6 py-4">
            <div class="flex space-x-4">
                <a href="{{ route('admin.members.pdf', $member) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-file-pdf mr-2"></i> Download Form
                </a>
                @if($member->admin_sign === 'No')
                <form action="{{ route('admin.members.approve', $member) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-check mr-2"></i> Approve Member
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.members.authority-deduct', $member->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    Download Authority to Deduct
                </a>

                @if($member->status !== 'suspended')
                <form action="{{ route('admin.members.suspend', $member) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        <i class="fas fa-ban mr-2"></i> Suspend Member
                    </button>
                </form>
                @else
                <form action="{{ route('admin.members.activate', $member) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-check-circle mr-2"></i> Activate Member
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Member Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Personal Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Personal Information</h3>


                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $member->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $member->phone_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Department</label>
                        <p class="text-gray-900">{{ $member->department->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Faculty</label>
                        <p class="text-gray-900">{{ $member->faculty->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Financial Information</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Monthly Savings</label>
                        <p class="text-gray-900">₦{{ number_format($member->monthly_savings, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Total Savings</label>
                        <p class="text-gray-900">₦{{ number_format($member->total_savings ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Share Capital</label>
                        <p class="text-gray-900">₦{{ number_format($member->share_subscription, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Join Date</label>
                        <p class="text-gray-900">{{ $member->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="px-6 py-4 border-t">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Transactions</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($member->transactions()->latest()->take(5)->get() as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₦{{ number_format($transaction->credit_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No transactions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
