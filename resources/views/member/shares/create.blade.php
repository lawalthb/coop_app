@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Purchase New Shares</h2>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Please correct the following errors:
                            </h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('member.shares.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share Type</label>
                        <select name="share_type_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select Share Type</option>
                            @foreach($shareTypes as $type)
                                <option value="{{ $type->id }}" data-price="{{ $type->price_per_unit }}" data-min="{{ $type->minimum_units }}" data-max="{{ $type->maximum_units }}">
                                    {{ $type->name }} (₦{{ number_format($type->price_per_unit) }} per unit)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Units</label>
                        <input type="number" name="number_of_units" min="1" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        <p class="text-sm text-gray-500 mt-1">Minimum: <span id="min-units">-</span> units | Maximum: <span id="max-units">-</span> units</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                        <p class="text-2xl font-bold text-purple-600">₦<span id="total-amount">0.00</span></p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('member.shares.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">Cancel</a>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Purchase Shares
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const shareTypeSelect = document.querySelector('select[name="share_type_id"]');
    const unitsInput = document.querySelector('input[name="number_of_units"]');
    const totalAmount = document.getElementById('total-amount');
    const minUnits = document.getElementById('min-units');
    const maxUnits = document.getElementById('max-units');

    function updateTotalAndLimits() {
        const selectedOption = shareTypeSelect.selectedOptions[0];
        if (selectedOption.value) {
            const pricePerUnit = parseFloat(selectedOption.dataset.price);
            const units = parseInt(unitsInput.value) || 0;
            totalAmount.textContent = (pricePerUnit * units).toLocaleString('en-NG', {minimumFractionDigits: 2});

            minUnits.textContent = selectedOption.dataset.min;
            maxUnits.textContent = selectedOption.dataset.max || 'Unlimited';

            unitsInput.min = selectedOption.dataset.min;
            if (selectedOption.dataset.max) {
                unitsInput.max = selectedOption.dataset.max;
            }
        }
    }

    shareTypeSelect.addEventListener('change', updateTotalAndLimits);
    unitsInput.addEventListener('input', updateTotalAndLimits);
});
</script>

@endsection
