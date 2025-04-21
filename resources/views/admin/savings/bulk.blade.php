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
        <label class="block text-sm font-medium text-gray-700">Year</label>
        <select name="year_id" id="year-select" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
            @foreach($years as $year)
                <option value="{{ $year->id }}" {{ date('Y') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Month</label>
        <select name="month_id" id="month-select" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
            @foreach($months as $month)
                <option value="{{ $month->id }}" {{ date('n') == $month->id ? 'selected' : '' }}>{{ $month->name }}</option>
            @endforeach
        </select>
    </div>
</div>


            <!-- Loading Overlay -->
            <div id="loading-overlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Updating savings amounts...</span>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="members-table-body">
                        @foreach($members as $member)
                        <tr data-member-id="{{ $member->id }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_members[]" value="{{ $member->id }}"
                                    class="member-checkbox rounded border-gray-300 text-purple-600">
                            </td>
                            <td class="px-6 py-4">{{ $member->surname }} {{ $member->firstname }}</td>
                            <td class="px-6 py-4">{{ $member->staff_no }}</td>
                            <td class="px-6 py-4">
                                <span class="amount-display">₦{{ number_format($member->monthly_savings, 2) }}</span>
                                <input type="hidden" name="member_amounts[{{ $member->id }}]" value="{{ $member->monthly_savings }}" class="amount-input">
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-blue-600 amount-source">Default</span>
                            </td>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox functionality
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.member-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Year and month selection change handler
        const yearSelect = document.getElementById('year-select');
        const monthSelect = document.getElementById('month-select');
        const loadingOverlay = document.getElementById('loading-overlay');

        // Function to update all member amounts based on selected year and month
        async function updateMemberAmounts() {
            console.log('Updating member amounts...');
            const yearId = yearSelect.value;
            const monthId = monthSelect.value;

            console.log(`Selected Year ID: ${yearId}, Month ID: ${monthId}`);

            if (!yearId || !monthId) {
                console.log('Year or month not selected, skipping update');
                return;
            }

            // Show loading overlay
            loadingOverlay.classList.remove('hidden');

            const tableRows = document.querySelectorAll('#members-table-body tr');
            console.log(`Found ${tableRows.length} member rows to update`);

            // Create an array of promises for all the fetch requests
            const updatePromises = Array.from(tableRows).map(async (row) => {
                const memberId = row.dataset.memberId;
                const amountDisplay = row.querySelector('.amount-display');
                const amountInput = row.querySelector('.amount-input');
                const amountSource = row.querySelector('.amount-source');

                console.log(`Fetching amount for member ${memberId}...`);

                try {
                    const url = `/admin/get-member-savings-amount/${memberId}/${yearId}/${monthId}`;
                    console.log(`Making request to: ${url}`);

                    const response = await fetch(url);
                    const data = await response.json();

                    console.log(`Response for member ${memberId}:`, data);

                    if (data.success) {
                        // Update the displayed amount
                        amountDisplay.textContent = '₦' + parseFloat(data.amount).toLocaleString('en-NG', {minimumFractionDigits: 2});
                        console.log(`Updated display amount for member ${memberId} to ${data.amount}`);

                        // Update the hidden input value
                        amountInput.value = data.amount;
                        console.log(`Updated input value for member ${memberId} to ${data.amount}`);

                        // Update the source indicator
                        if (data.source === 'settings') {
                            amountSource.textContent = 'Settings';
                            amountSource.className = 'text-xs text-green-600 amount-source';
                            console.log(`Source for member ${memberId} is settings`);
                        } else {
                            amountSource.textContent = 'Default';
                            amountSource.className = 'text-xs text-blue-600 amount-source';
                            console.log(`Source for member ${memberId} is default profile`);
                        }
                    } else {
                        console.error(`Error in response for member ${memberId}:`, data.message);
                    }
                } catch (error) {
                    console.error(`Error fetching amount for member ${memberId}:`, error);
                }
            });

            // Wait for all updates to complete
            await Promise.all(updatePromises);

            // Hide loading overlay
            loadingOverlay.classList.add('hidden');

            console.log('Member amounts update completed');
        }

        // Add event listeners to the year and month selects
        yearSelect.addEventListener('change', function() {
            console.log('Year selection changed');
            updateMemberAmounts();
        });

        monthSelect.addEventListener('change', function() {
            console.log('Month selection changed');
            updateMemberAmounts();
        });

        // Initial update if values are already selected
        console.log('Checking for initial values...');
        if (yearSelect.value && monthSelect.value) {
            console.log('Initial values found, performing initial update');
            updateMemberAmounts();
        } else {
            console.log('No initial values, skipping initial update');
        }
    });
</script>
@endsection
