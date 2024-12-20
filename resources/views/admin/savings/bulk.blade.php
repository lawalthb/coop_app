@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-purple-600">
            <h2 class="text-xl font-semibold text-white">Bulk Savings Entry</h2>
        </div>

        <form action="{{ route('admin.savings.bulk.store') }}" method="POST" class="p-6">
            @csrf

            <div class="mb-6 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Month</label>
                    <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        @foreach($months as $month)
                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-purple-600">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($members as $member)
                        <tr>
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_members[]" value="{{ $member->id }}"
                                    class="member-checkbox rounded border-gray-300 text-purple-600">
                            </td>
                            <td class="px-6 py-4">{{ $member->surname }} {{ $member->firstname }}</td>
                            <td class="px-6 py-4">{{ $member->staff_no }}</td>
                            <td class="px-6 py-4">₦{{ number_format($member->monthly_savings, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Post Selected Savings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.member-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endsection
