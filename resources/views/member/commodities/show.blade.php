@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('commodities.index') }}" class="text-purple-600 hover:text-purple-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Commodities
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                @if($commodity->image)
                <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="w-full h-64 md:h-full object-cover">
                @else
                <div class="w-full h-64 md:h-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-shopping-basket text-gray-400 text-6xl"></i>
                </div>
                @endif
            </div>

            <div class="md:w-1/2 p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $commodity->name }}</h1>

                <div class="flex items-center mb-4">
                    <span class="text-2xl font-bold text-purple-700 mr-2">₦{{ number_format($commodity->price, 2) }}</span>
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                        {{ $commodity->quantity_available }} available
                    </span>
                </div>

                @if($commodity->start_date || $commodity->end_date)
                <div class="mb-4 text-sm text-gray-600">
                    @if($commodity->start_date)
                    <div><span class="font-medium">Start Date:</span> {{ $commodity->start_date->format('M d, Y') }}</div>
                    @endif
                    @if($commodity->end_date)
                    <div><span class="font-medium">End Date:</span> {{ $commodity->end_date->format('M d, Y') }}</div>
                    @endif
                </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700">{{ $commodity->description }}</p>
                </div>

                <!-- Payment Options Section -->
                @if($commodity->allow_installment)
                <div class="mb-6 p-4 bg-purple-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Options</h3>
                    <div class="text-sm text-gray-700">
                        <p>This commodity can be purchased with installment payments.</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Maximum installment period: <span class="font-medium">{{ $commodity->max_installment_months }} months</span></li>
                            <li>Minimum initial deposit: <span class="font-medium">{{ $commodity->initial_deposit_percentage }}%</span></li>
                        </ul>
                    </div>
                </div>
                @endif

                <form action="{{ route('commodities.subscribe', $commodity) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="{{ $commodity->quantity_available }}" value="1" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        <p class="text-sm text-gray-500 mt-1">Maximum available: {{ $commodity->quantity_available }}</p>
                    </div>

                    <!-- Payment Type Selection -->
                    @if($commodity->allow_installment)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="payment_full" name="payment_type" value="full" checked
                                       class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300"
                                       onchange="toggleInstallmentFields(false)">
                                <label for="payment_full" class="ml-2 block text-sm text-gray-700">
                                    Full Payment
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="payment_installment" name="payment_type" value="installment"
                                       class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300"
                                       onchange="toggleInstallmentFields(true)">
                                <label for="payment_installment" class="ml-2 block text-sm text-gray-700">
                                    Installment Payment
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Installment Fields (initially hidden) -->
                    <div id="installment_fields" class="hidden space-y-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="initial_deposit" class="block text-sm font-medium text-gray-700 mb-1">Initial Deposit (₦)</label>
                            <input type="number" name="initial_deposit" id="initial_deposit" min="{{ $commodity->price * $commodity->initial_deposit_percentage / 100 }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                            <p class="text-sm text-gray-500 mt-1">
                                Minimum deposit: ₦{{ number_format($commodity->price * $commodity->initial_deposit_percentage / 100, 2) }}
                                ({{ $commodity->initial_deposit_percentage }}% of item price)
                            </p>
                        </div>

                        <div class="text-sm text-gray-700">
                            <p>Payment Summary:</p>
                            <div class="mt-2 space-y-1">
                                <div id="total_price">Total Price: ₦<span>{{ number_format($commodity->price, 2) }}</span></div>
                                <div id="deposit_amount">Initial Deposit: ₦<span>0.00</span></div>
                                <div id="remaining_amount">Remaining Amount: ₦<span>{{ number_format($commodity->price, 2) }}</span></div>
                                <div id="monthly_payment">Monthly Payment ({{ $commodity->max_installment_months }} months): ₦<span>{{ number_format($commodity->price / $commodity->max_installment_months, 2) }}</span></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Subscription (Optional)</label>
                        <textarea name="reason" id="reason" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                  style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Subscribe Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($commodity->allow_installment)
<script>
    // Function to toggle installment fields visibility
    function toggleInstallmentFields(show) {
        const installmentFields = document.getElementById('installment_fields');
        if (show) {
            installmentFields.classList.remove('hidden');
        } else {
            installmentFields.classList.add('hidden');
        }
    }

    // Function to update payment summary
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const initialDepositInput = document.getElementById('initial_deposit');
        const unitPrice = {{ $commodity->price }};
        const maxMonths = {{ $commodity->max_installment_months }};
        const minDepositPercentage = {{ $commodity->initial_deposit_percentage }};

        function updateSummary() {
            const quantity = parseInt(quantityInput.value) || 1;
            const totalPrice = unitPrice * quantity;

            // Update minimum deposit value
            const minDeposit = totalPrice * minDepositPercentage / 100;
            initialDepositInput.min = minDeposit;

            // Get current deposit value
            let depositAmount = parseFloat(initialDepositInput.value) || minDeposit;
            if (depositAmount < minDeposit) {
                depositAmount = minDeposit;
                initialDepositInput.value = minDeposit;
            }

            const remainingAmount = totalPrice - depositAmount;
            const monthlyPayment = remainingAmount / maxMonths;

            // Update summary display
            document.querySelector('#total_price span').textContent = totalPrice.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.querySelector('#deposit_amount span').textContent = depositAmount.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.querySelector('#remaining_amount span').textContent = remainingAmount.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.querySelector('#monthly_payment span').textContent = monthlyPayment.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.querySelector('#monthly_payment').textContent = `Monthly Payment (${maxMonths} months): ₦${monthlyPayment.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }

        // Add event listeners
        if (quantityInput) {
            quantityInput.addEventListener('input', updateSummary);
        }

        if (initialDepositInput) {
            initialDepositInput.addEventListener('input', updateSummary);
        }

        // Initialize summary
        updateSummary();
    });
</script>
@endif
@endsection
