@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Add New Commodity</h2>
            </div>

            <form action="{{ route('admin.commodities.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name*</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                  style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (₦)*</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div>
                        <label for="quantity_available" class="block text-sm font-medium text-gray-700">Slot Available*</label>
                        <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', 0) }}" required min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                                     file:mr-4 file:py-2 file:px-4
                                     file:rounded-md file:border-0
                                     file:text-sm file:font-semibold
                                     file:bg-purple-50 file:text-purple-700
                                     hover:file:bg-purple-100">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="purchase_amount" class="block text-sm font-medium text-gray-700">Purchase Amount (₦)</label>
                            <input type="number" name="purchase_amount" id="purchase_amount" value="{{ old('purchase_amount') }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="target_sales_amount" class="block text-sm font-medium text-gray-700">Target Sales Amount (₦)</label>
                            <input type="number" name="target_sales_amount" id="target_sales_amount" value="{{ old('target_sales_amount') }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="profit_amount" class="block text-sm font-medium text-gray-700">Profit Amount (₦)</label>
                            <input type="number" name="profit_amount" id="profit_amount" value="{{ old('profit_amount') }}" step="0.01"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Active</span>
                        </label>
                    </div>

<div class="mt-6 p-4 bg-gray-50 rounded-lg">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Options</h3>

    <div class="mb-4">
        <div class="flex items-center">
            <input type="checkbox" name="allow_installment" id="allow_installment" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
            <label for="allow_installment" class="ml-2 block text-sm text-gray-900">
                Allow Installment Payments
            </label>
        </div>
        <p class="mt-1 text-sm text-gray-500">
            Enable this option to allow members to pay for this commodity in installments.
        </p>
    </div>

    <div id="installment_options" class="pl-6 space-y-4 hidden">
        <div>
            <label for="max_installment_months" class="block text-sm font-medium text-gray-700">
                Maximum Installment Period (Months)
            </label>
            <input type="number" name="max_installment_months" id="max_installment_months" min="1" max="36" value="3"
                   class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            <p class="mt-1 text-xs text-gray-500">
                The maximum number of months members can spread their payments over.
            </p>
        </div>

        <div>
            <label for="initial_deposit_percentage" class="block text-sm font-medium text-gray-700">
                Initial Deposit Percentage (%)
            </label>
            <input type="number" name="initial_deposit_percentage" id="initial_deposit_percentage" min="0" max="100" value="20" step="5"
                   class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            <p class="mt-1 text-xs text-gray-500">
                The percentage of the total price that members must pay as initial deposit.
            </p>
        </div>

        <div id="payment_preview" class="mt-4 p-4 bg-white border border-gray-200 rounded-md hidden">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Preview</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Total Price:</span>
                    <span id="preview_total_price">₦0.00</span>
                </div>
                <div class="flex justify-between">
                    <span>Initial Deposit:</span>
                    <span id="preview_initial_deposit">₦0.00</span>
                </div>
                <div class="flex justify-between">
                    <span>Remaining Amount:</span>
                    <span id="preview_remaining">₦0.00</span>
                </div>
                <div class="flex justify-between font-medium">
                    <span>Monthly Payment:</span>
                    <span id="preview_monthly">₦0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>


                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.commodities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </a>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Create Commodity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allowInstallmentCheckbox = document.getElementById('allow_installment');
        const installmentOptions = document.getElementById('installment_options');
        const priceInput = document.getElementById('price');
        const maxMonthsInput = document.getElementById('max_installment_months');
        const initialDepositInput = document.getElementById('initial_deposit_percentage');

         const paymentPreview = document.getElementById('payment_preview');

        // Function to update payment preview
        function updatePaymentPreview() {
            if (!allowInstallmentCheckbox.checked) {
                paymentPreview.classList.add('hidden');
                return;
            }

            const price = parseFloat(priceInput.value) || 0;
            const months = parseInt(maxMonthsInput.value) || 1;
            const depositPercentage = parseFloat(initialDepositInput.value) || 0;

            if (price > 0) {
                const initialDeposit = (price * depositPercentage) / 100;
                const remainingAmount = price - initialDeposit;
                const monthlyPayment = remainingAmount / months;

                document.getElementById('preview_total_price').textContent = '₦' + price.toFixed(2);
                document.getElementById('preview_initial_deposit').textContent = '₦' + initialDeposit.toFixed(2);
                document.getElementById('preview_remaining').textContent = '₦' + remainingAmount.toFixed(2);
                document.getElementById('preview_monthly').textContent = '₦' + monthlyPayment.toFixed(2);

                paymentPreview.classList.remove('hidden');
            } else {
                paymentPreview.classList.add('hidden');
            }
        }

        // Toggle installment options visibility
        allowInstallmentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                installmentOptions.classList.remove('hidden');
                updatePaymentPreview();
            } else {
                installmentOptions.classList.add('hidden');
            }
        });

        // Update preview when inputs change
        priceInput.addEventListener('input', updatePaymentPreview);
        maxMonthsInput.addEventListener('input', updatePaymentPreview);
        initialDepositInput.addEventListener('input', updatePaymentPreview);
    });
</script>

@endsection
