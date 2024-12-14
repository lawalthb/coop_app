<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Loan 222 Type Name</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name', $loanType->name ?? '') }}" required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="required_active_savings_months" class="block text-sm font-medium text-gray-700">Required Savings Months</label>
            <input type="number" name="required_active_savings_months" id="required_active_savings_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('required_active_savings_months', $loanType->required_active_savings_months ?? 6) }}" required>
        </div>

        <div>
            <label for="savings_multiplier" class="block text-sm font-medium text-gray-700">Savings Multiplier</label>
            <input type="number" step="0.01" name="savings_multiplier" id="savings_multiplier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('savings_multiplier', $loanType->savings_multiplier ?? 2) }}" required>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="interest_rate_12_months" class="block text-sm font-medium text-gray-700">Interest Rate (12 months)</label>
            <input type="number" step="0.01" name="interest_rate_12_months" id="interest_rate_12_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('interest_rate_12_months', $loanType->interest_rate_12_months ?? '') }}" required>
        </div>

        <div>
            <label for="interest_rate_18_months" class="block text-sm font-medium text-gray-700">Interest Rate (18 months)</label>
            <input type="number" step="0.01" name="interest_rate_18_months" id="interest_rate_18_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('interest_rate_18_months', $loanType->interest_rate_18_months ?? '') }}" required>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="minimum_amount" class="block text-sm font-medium text-gray-700">Minimum Amount</label>
            <input type="number" step="0.01" name="minimum_amount" id="minimum_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('minimum_amount', $loanType->minimum_amount ?? '') }}" required>
        </div>

        <div>
            <label for="maximum_amount" class="block text-sm font-medium text-gray-700">Maximum Amount</label>
            <input type="number" step="0.01" name="maximum_amount" id="maximum_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('maximum_amount', $loanType->maximum_amount ?? '') }}" required>
        </div>
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="allow_early_payment" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('allow_early_payment', $loanType->allow_early_payment ?? true) ? 'checked' : '' }}>
            <span class="ml-2 text-sm text-gray-600">Allow Early Payment</span>
        </label>
    </div>

    <div class="flex justify-end gap-4">
        <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Loan Type</button>
    </div>
</div>
