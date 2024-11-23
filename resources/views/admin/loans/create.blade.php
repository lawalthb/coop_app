@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">New Loan Application</h2>
            </div>

            <form action="{{ route('admin.loans.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Member Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Member</label>
                        <select name="user_id" id="member-select" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            <option value="">Select a member</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}">
                                {{ $member->surname }} {{ $member->firstname }} - {{ $member->staff_no }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Loan Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loan Type</label>
                        <select name="loan_type_id" id="loan-type" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            <option value="">Select loan type</option>
                            @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}"
                                data-interest="{{ $type->interest_rate }}"
                                data-min="{{ $type->minimum_amount }}"
                                data-max="{{ $type->maximum_amount }}"
                                data-duration="{{ $type->max_duration }}">
                                {{ $type->name }} ({{ $type->interest_rate }}% Interest)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Loan Details -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Loan Amount</label>
                            <input type="number" name="amount" id="amount" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            <p class="mt-1 text-sm text-gray-500" id="amount-range"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (Months)</label>
                            <input type="number" name="duration" id="duration" min="1" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            <p class="mt-1 text-sm text-gray-500" id="max-duration"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Purpose of Loan</label>
                        <textarea name="purpose" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;"></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('loan-type').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const minAmount = parseFloat(selectedOption.dataset.min);
        const maxAmount = parseFloat(selectedOption.dataset.max);
        const maxDuration = parseInt(selectedOption.dataset.duration);

        document.getElementById('amount-range').textContent =
            `Amount range: ₦${minAmount.toLocaleString()} - ₦${maxAmount.toLocaleString()}`;
        document.getElementById('max-duration').textContent =
            `Maximum duration: ${maxDuration} months`;

        const amountInput = document.getElementById('amount');
        amountInput.min = minAmount;
        amountInput.max = maxAmount;

        const durationInput = document.getElementById('duration');
        durationInput.max = maxDuration;
    });
</script>
@endpush
@endsection
