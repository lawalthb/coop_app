<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Savings Amount</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₦</span>
            <input type="number" name="monthly_savings" value="{{ old('monthly_savings') }}" class="w-full pl-8 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Share Subscription Amount</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₦</span>
            <input type="number" name="share_subscription" value="{{ old('share_subscription') }}" class="w-full pl-8 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Month to Commence</label>
        <input type="month" name="month_commence" value="{{ old('month_commence') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>
</div>
