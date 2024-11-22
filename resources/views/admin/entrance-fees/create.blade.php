@extends('layouts.admin')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.member-select').select2({
            placeholder: 'Search for a member...',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection



@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Add New Entrance Fee</h2>
            </div>

            <form action="{{ route('admin.entrance-fees.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Member</label>
                        <select name="user_id" class="member-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Select a member</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}">
                                {{ $member->surname }} {{ $member->firstname }} - {{ $member->member_no }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <script>
                        $(document).ready(function() {
                            alert('lawal');
                            $('.member-select').select2({
                                placeholder: 'Search for a member...',
                                allowClear: true
                            });
                        });
                    </script>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount (₦)</label>
                        <input type="number" name="amount" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;" required value="2000">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Month</label>
                            <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;" required>
                                @foreach($months as $month)
                                <option value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;" required>
                                @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remark</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" checked name="approve_member" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <span class="ml-2 text-sm text-gray-600">Approve account and sign this member's form</span>
                    </label>
                </div>
                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.entrance-fees') }}" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-200 mr-4">Cancel</a>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Save Fee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
