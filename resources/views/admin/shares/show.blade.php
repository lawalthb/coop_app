@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Share Purchase Details</h2>
                <a href="{{ route('admin.shares.index') }}" class="text-purple-600 hover:text-purple-700">
                    <i class="fas fa-arrow-left mr-1"></i>Back to List
                </a>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Certificate Number</h3>
                        <p class="mt-1">{{ $share->certificate_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $share->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($share->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($share->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Member</h3>
                        <p class="mt-1">{{ $share->user->surname }} {{ $share->user->firstname }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Share Type</h3>
                        <p class="mt-1">{{ $share->shareType->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Number of Units</h3>
                        <p class="mt-1">{{ $share->number_of_units }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Unit Price</h3>
                        <p class="mt-1">₦{{ number_format($share->unit_price, 2) }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Amount Paid</h3>
                    <p class="mt-1">₦{{ number_format($share->amount_paid, 2) }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Posted By</h3>
                        <p class="mt-1">{{ $share->postedBy->surname }} {{ $share->postedBy->firstname }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Posted Date</h3>
                        <p class="mt-1">{{ $share->created_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>

                @if($share->approved_by)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Approved/Rejected By</h3>
                        <p class="mt-1">{{ $share->approvedBy->surname }} {{ $share->approvedBy->firstname }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Approval Date</h3>
                        <p class="mt-1">{{ $share->approved_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
                @endif

                @if($share->remark)
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Remark</h3>
                    <p class="mt-1">{{ $share->remark }}</p>
                </div>
                @endif

                @if($share->status === 'pending')
                <div class="flex justify-end space-x-4">
                    <form action="{{ route('admin.shares.approve', $share) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                            onclick="return confirm('Are you sure you want to approve this share purchase?')">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.shares.reject', $share) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to reject this share purchase?')">
                            Reject
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
