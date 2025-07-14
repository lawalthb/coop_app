@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">

            <h2 class="text-2xl font-semibold mb-6">New Share Withdrawal</h2>

            <form action="{{ route('admin.shares.withdrawals.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select name="user_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->surname }} {{ $member->firstname }} - ({{ $member->member_no }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share Withdraw Type</label>
                        <select name="share_type_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select Share Type</option>
                            @foreach($shareTypes as $type)
                            <option value="{{ $type->id }}" {{ old('share_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} (Min: ₦{{ number_format($type->minimum_amount, 2) }})
                            </option>
                            @endforeach
                        </select>
                        @error('share_type_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount</label>
                        <input type="number" name="amount_paid" value="{{ old('amount_paid') }}" required step="0.01" min="0"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"
                            placeholder="Enter withdrawal amount_paid">
                        @error('amount_paid')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"
                            placeholder="Enter bank name">
                        @error('bank_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"
                            placeholder="Enter account number">
                        @error('account_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                        <input type="text" name="account_name" value="{{ old('account_name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"
                            placeholder="Enter account name">
                        @error('account_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                        <select name="month_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select Month</option>
                            @foreach($months as $month)
                            <option value="{{ $month->id }}" {{ old('month_id', date('n')) == $month->id ? 'selected' : '' }}>
                                {{ $month->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('month_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select name="year_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ old('year_id', date('Y')) == $year->year ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Withdrawal</label>
                        <textarea name="remark" rows="3" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"
                            placeholder="Enter reason for withdrawal">{{ old('remark') }}</textarea>
                        @error('remark')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('admin.shares.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Withdrawals
                        </a>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Withdrawal Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-populate account name when member is selected
    const memberSelect = document.querySelector('select[name="user_id"]');
    const accountNameInput = document.querySelector('input[name="account_name"]');

    memberSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const memberName = selectedOption.text.split(' - ')[0]; // Get name part before member number
            accountNameInput.value = memberName;
        } else {
            accountNameInput.value = '';
        }
    });

    // Format account number input (remove non-numeric characters)
    const accountNumberInput = document.querySelector('input[name="account_number"]');
    accountNumberInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, ''); // Remove non-digits
    });

    // Format amount input
    const amountInput = document.querySelector('input[name="amount"]');
    amountInput.addEventListener('input', function() {
        // Ensure positive numbers only
        if (this.value < 0) {
            this.value = 0;
        }
    });
});
</script>

<style>
/* Custom styles for better form appearance */
.form-group {
    margin-bottom: 1.5rem;
}

select:focus, input:focus, textarea:focus {
    outline: none;
    border-color: #7C3AED;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

/* Error state styling */
.error input, .error select, .error textarea {
    border-color: #EF4444;
}

/* Success state styling */
.success input, .success select, .success textarea {
    border-color: #10B981;
}

/* Loading state for submit button */
.btn-loading {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .flex.justify-between {
        flex-direction: column;
        gap: 1rem;
    }

    .flex.justify-between > * {
        width: 100%;
        text-align: center;
    }
}
</style>
@endsection
