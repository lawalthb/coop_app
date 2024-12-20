@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Post New Savings</h2>
            </div>

            <form action="{{ route('admin.savings.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Member Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Member</label>
                        <select name="user_id" id="member-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;" >
                            <option value="">Select a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" data-monthly="{{ $member->monthly_savings }}">
                                    {{ $member->surname }} {{ $member->firstname }} - ₦{{ number_format($member->monthly_savings, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amount Section -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Monthly Amount</label>
                            <input type="text" id="default-amount" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Custom Amount</label>
                            <input type="number" name="amount" id="custom-amount" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                            <p class="mt-1 text-sm text-gray-500">Leave blank to use default monthly amount</p>
                        </div>
                    </div>

                    <!-- Saving Type -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Saving Type</label>
                            <select name="saving_type_id" id="saving-type" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                @foreach($savingTypes as $type)
                                    <option value="{{ $type->id }}" data-interest="{{ $type->interest_rate }}">
                                        {{ $type->name }} ({{ $type->interest_rate }}% Interest)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest Rate</label>
                            <input type="text" id="interest-rate" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <!-- Period Selection -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Month</label>
                            <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                @foreach($months as $month)
                                <option value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;"></textarea>
                    </div>

                    <!-- Add this script at the bottom of your form -->
                    <script>
                        document.getElementById('member-select').addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const monthlyAmount = selectedOption.dataset.monthly;
                            document.getElementById('default-amount').value = '₦' + parseFloat(monthlyAmount).toLocaleString('en-NG', {minimumFractionDigits: 2});
                        });

                        document.getElementById('saving-type').addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const interestRate = selectedOption.dataset.interest;
                            document.getElementById('interest-rate').value = interestRate + '%';
                        });
                    </script>
                </div>
                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.savings') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Post Savings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
