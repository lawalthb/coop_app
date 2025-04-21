@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <select name="user_id" id="member-select" class="select2-dropdown mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->surname }} {{ $member->firstname }} - {{ $member->member_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Period Selection -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <select name="year_id" id="year-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Month</label>
                            <select name="month_id" id="month-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Month</option>
                                @foreach($months as $month)
                                <option value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Amount Section -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Monthly Amount</label>
                            <input type="text" id="default-amount" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <p class="mt-1 text-sm text-gray-500" id="amount-source"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Custom Amount</label>
                            <input type="number" name="amount" id="custom-amount" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <p class="mt-1 text-sm text-gray-500">Leave blank to use default monthly amount</p>
                        </div>
                    </div>

                    <!-- Saving Type -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Saving Type</label>
                            <select name="saving_type_id" id="saving-type" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                @foreach($savingTypes as $type)
                                    <option value="{{ $type->id }}" data-interest="{{ $type->interest_rate }}">
                                        {{ $type->name }} ({{ $type->interest_rate }}% Interest)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest Rate</label>
                            <input type="text" id="interest-rate" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px;"></textarea>
                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for member dropdown
        $('.select2-dropdown').select2({
            placeholder: "Search for a member...",
            allowClear: true,
            width: '100%'
        });

        // Initialize interest rate display
        const savingTypeSelect = document.getElementById('saving-type');
        const interestRateInput = document.getElementById('interest-rate');

        function updateInterestRate() {
            const selectedOption = savingTypeSelect.options[savingTypeSelect.selectedIndex];
            const interestRate = selectedOption.dataset.interest;
            interestRateInput.value = interestRate + '%';
        }

        // Set initial interest rate
        updateInterestRate();

        // Update interest rate when saving type changes
        savingTypeSelect.addEventListener('change', updateInterestRate);

        // Variables for the savings amount lookup
        const memberSelect = document.getElementById('member-select');
        const yearSelect = document.getElementById('year-select');
        const monthSelect = document.getElementById('month-select');
        const defaultAmountInput = document.getElementById('default-amount');
        const amountSourceText = document.getElementById('amount-source');

        // Function to fetch the savings amount
        async function fetchSavingsAmount() {
            const memberId = memberSelect.value;
            const yearId = yearSelect.value;
            const monthId = monthSelect.value;

            // Only proceed if all three values are selected
            if (!memberId || !yearId || !monthId) {
                defaultAmountInput.value = '';
                amountSourceText.textContent = '';
                return;
            }

            try {
                const response = await fetch(`/admin/get-member-savings-amount/${memberId}/${yearId}/${monthId}`);
                const data = await response.json();

                if (data.success) {
                    defaultAmountInput.value = '₦' + parseFloat(data.amount).toLocaleString('en-NG', {minimumFractionDigits: 2});

                    if (data.source === 'settings') {
                        amountSourceText.textContent = 'Amount from member\'s savings settings';
                        amountSourceText.className = 'mt-1 text-sm text-green-600';
                    } else {
                        amountSourceText.textContent = 'Default amount from member profile';
                        amountSourceText.className = 'mt-1 text-sm text-blue-600';
                    }
                } else {
                    defaultAmountInput.value = '';
                    amountSourceText.textContent = data.message || 'Unable to fetch savings amount';
                    amountSourceText.className = 'mt-1 text-sm text-red-600';
                }
            } catch (error) {
                console.error('Error fetching savings amount:', error);
                defaultAmountInput.value = '';
                amountSourceText.textContent = 'Error fetching savings amount';
                amountSourceText.className = 'mt-1 text-sm text-red-600';
            }
        }

        // Add event listeners to trigger the fetch
        memberSelect.addEventListener('change', fetchSavingsAmount);
        yearSelect.addEventListener('change', fetchSavingsAmount);
        monthSelect.addEventListener('change', fetchSavingsAmount);
    });
</script>
@endsection
