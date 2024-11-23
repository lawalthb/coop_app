@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Transfer Shares</h2>
            </div>

            <form action="{{ route('admin.shares.transfer.process') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- From Member -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">From Member</label>
                        <select name="from_user_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                            <option value="">Select transferring member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->surname }} {{ $member->firstname }} - {{ $member->staff_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Member -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">To Member</label>
                        <select name="to_user_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                            <option value="">Select receiving member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->surname }} {{ $member->firstname }} - {{ $member->staff_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Number of Shares -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Number of Shares to Transfer</label>
                        <input type="number" name="number_of_shares" min="1" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.shares.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Process Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
