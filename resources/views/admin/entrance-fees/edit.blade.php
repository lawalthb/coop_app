@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Entrance Fee</h2>
            </div>

            <form action="{{ route('admin.member.entrance-fees.update', $entranceFee) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Member</label>
                        <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ $entranceFee->user_id == $member->id ? 'selected' : '' }}>
                                    {{ $member->surname }} {{ $member->firstname }} - {{ $member->member_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" name="amount" step="0.01" value="{{ $entranceFee->amount }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Month</label>
                            <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                @foreach($months as $month)
                                    <option value="{{ $month->id }}" {{ $entranceFee->month_id == $month->id ? 'selected' : '' }}>
                                        {{ $month->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ $entranceFee->year_id == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remark</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">{{ $entranceFee->remark }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.member.entrance-fees') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-200 mr-4">Cancel</a>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Update Fee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
