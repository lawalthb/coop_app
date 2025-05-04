@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Record Member Withdrawal</h2>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.withdrawals.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Member and Saving Type Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Member*</label>
                            <select name="user_id" id="member-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                                <option value="">Select a member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->surname }} {{ $member->firstname }} ({{ $member->member_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Saving Type*</label>
                            <select name="saving_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                                <option value="">Select saving type</option>
                                @foreach($savingTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('saving_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Amount and Period Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount*</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                   
                                </div>
                                <input
                                    type="text"
                                    name="amount"
                                    id="amount-input"
                                    value="{{ old('amount') }}"
                                    class="block w-full pl-8 pr-12 rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                                    placeholder="0.00"
                                    required
                                    style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;"
                                    inputmode="decimal"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">NGN</span>
                                </div>
                            </div>
                            <div id="formatted-amount" class="mt-1 text-sm text-purple-600"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Month*</label>
                            <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                                <option value="">Select month</option>
                                @foreach($months as $month)
                                    <option value="{{ $month->id }}" {{ old('month_id') == $month->id ? 'selected' : '' }}>
                                        {{ $month->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year*</label>
                            <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                                <option value="">Select year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bank Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name*</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number*</label>
                                <input type="text" name="account_number" value="{{ old('account_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Name*</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">
                            </div>
                        </div>
                    </div>

                    <!-- Reason Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Withdrawal*</label>
                        <textarea name="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" rows="3" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px; padding: 10px;">{{ old('reason') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Record Withdrawal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Format currency input
    const amountInput = document.getElementById('amount-input');
    const formattedAmount = document.getElementById('formatted-amount');

    amountInput.addEventListener('input', function(e) {
        // Remove non-numeric characters except decimal point
        let value = this.value.replace(/[^\d.]/g, '');

        // Ensure only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        // Update the input value
        this.value = value;

        // Format the value for display
        if (value) {
            const numValue = parseFloat(value);
            if (!isNaN(numValue)) {
                formattedAmount.textContent = 'Amount: ' + new Intl.NumberFormat('en-NG', {
                    style: 'currency',
                    currency: 'NGN'
                }).format(numValue);
            } else {
                formattedAmount.textContent = '';
            }
        } else {
            formattedAmount.textContent = '';
        }
    });

    // Optional: Add JavaScript to auto-fill account details based on member selection
    document.getElementById('member-select').addEventListener('change', function() {
        // This would require an AJAX call to fetch member bank details
        // For now, this is just a placeholder for potential future enhancement
    });
</script>
@endsection
