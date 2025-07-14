@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Record Loan Repayment</h2>
                    <a href="{{ route('admin.loans.show', $loan) }}"
                       class="inline-flex items-center px-3 py-2 border border-white text-sm font-medium rounded-md text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Loan
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Loan Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Loan Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Loan Reference</p>
                            <p class="font-medium">{{ $loan->reference }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Member</p>
                            <p class="font-medium">{{ $loan->user->surname }} {{ $loan->user->firstname }}</p>
                            <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="font-medium">₦{{ number_format($loan->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Outstanding Balance</p>
                            <p class="font-medium text-red-600">₦{{ number_format($loan->balance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Monthly Payment</p>
                            <p class="font-medium">₦{{ number_format($loan->monthly_payment, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Amount Paid</p>
                            <p class="font-medium text-green-600">₦{{ number_format($loan->amount_paid, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Repayment Form -->
                <form action="{{ route('admin.loans.repayments.store', $loan) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">₦</span>
                                <input type="number"
                                       id="amount"
                                       name="amount"
                                       step="0.01"
                                       min="0"
                                       max="{{ $loan->balance }}"
                                       value="{{ old('amount', $loan->monthly_payment) }}"
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                       required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Maximum: ₦{{ number_format($loan->balance, 2) }}</p>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="payment_date"
                                   name="payment_date"
                                   value="{{ old('payment_date', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                   required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Month -->
                        <div>
                            <label for="month_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Month <span class="text-red-500">*</span>
                            </label>
                            <select id="month_id"
                                    name="month_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                <option value="">Select Month</option>
                                @foreach($months as $month)
                                    <option value="{{ $month->id }}" {{ old('month_id', date('n')) == $month->id ? 'selected' : '' }}>
                                        {{ $month->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('month_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div>
                            <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Year <span class="text-red-500">*</span>
                            </label>
                            <select id="year_id"
                                    name="year_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id', date('Y')) == $year->year ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="md:col-span-2">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_method"
                                    name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="deduction" {{ old('payment_method') == 'deduction' ? 'selected' : '' }}>Salary Deduction</option>
                                <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="pos" {{ old('payment_method') == 'pos' ? 'selected' : '' }}>POS</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Add any additional notes about this payment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.loans.show', $loan) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-select current month and year based on payment date
document.getElementById('payment_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const month = selectedDate.getMonth() + 1; // JavaScript months are 0-indexed
    const year = selectedDate.getFullYear();

    // Try to select the corresponding month
    const monthSelect = document.getElementById('month_id');
    for (let option of monthSelect.options) {
        if (option.text.toLowerCase().includes(selectedDate.toLocaleString('default', { month: 'long' }).toLowerCase())) {
            option.selected = true;
            break;
        }
    }

    // Try to select the corresponding year
    const yearSelect = document.getElementById('year_id');
    for (let option of yearSelect.options) {
        if (option.text == year) {
            option.selected = true;
            break;
        }
    }
});
</script>
@endsection
