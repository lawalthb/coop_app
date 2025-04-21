@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Application</h2>
            </div>

            <!-- Error Messages Section -->
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6 mt-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-6 mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('member.loans.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loan Type</label>
                        <select name="loan_type_id" id="loan_type_id" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                            <option value="">Select Loan Type</option>
                            @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}"
                                data-guarantors="{{ $type->no_guarantors }}"
                                data-application-fee="{{ $type->application_fee }}"
                                data-min-amount="{{ $type->minimum_amount }}"
                                data-max-amount="{{ $type->maximum_amount }}"
                                data-max-duration="{{ $type->duration_months }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <input type="number" name="amount" id="loanAmount"
                            class="w-full rounded-lg border-gray-300" required
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                        <div class="mt-1 text-sm text-purple-600" id="amountInWords"></div>
                        <div class="mt-1 text-sm text-purple-600" id="formattedAmount" style="display: none;"></div>
                        <div class="mt-1 text-sm text-gray-500" id="amountRange"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration (months)</label>
                        <input type="number" name="duration" id="loanDuration" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                        <div class="mt-1 text-sm text-gray-500" id="durationLimit"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purpose of Loan</label>
                    <textarea name="purpose" rows="3" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;"></textarea>
                </div>

                <!-- Add this container for dynamic guarantor fields -->
                <div id="guarantorsContainer"></div>

                <!-- Application Fee Agreement -->
                <div id="applicationFeeContainer" class="hidden mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-start">
                        <div class="flex items-center h-5 mt-1">
                            <input type="checkbox" name="application_fee_agreement" id="application_fee_agreement" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" required>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="application_fee_agreement" class="font-medium text-gray-700">Application Fee Agreement</label>
                            <p class="text-gray-500" id="application_fee_text">I agree to pay the application fee of ₦0.00 if my loan is approved, before the loan amount is disbursed.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">

                    <!-- Add this JavaScript at the top of the form -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const loanTypeSelect = document.getElementById('loan_type_id');
                            const guarantorsContainer = document.getElementById('guarantorsContainer');
                            const applicationFeeContainer = document.getElementById('applicationFeeContainer');
                            const applicationFeeText = document.getElementById('application_fee_text');
                            const applicationFeeCheckbox = document.getElementById('application_fee_agreement');
                            const loanAmountInput = document.getElementById('loanAmount');
                            const amountRangeText = document.getElementById('amountRange');
                            const loanDurationInput = document.getElementById('loanDuration');
                            const durationLimitText = document.getElementById('durationLimit');

                            loanTypeSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const noOfGuarantors = selectedOption.dataset.guarantors;
                                const applicationFee = selectedOption.dataset.applicationFee;
                                const minAmount = parseFloat(selectedOption.dataset.minAmount);
                                const maxAmount = parseFloat(selectedOption.dataset.maxAmount);
                                const maxDuration = parseInt(selectedOption.dataset.maxDuration);

                                // Update guarantors section
                                guarantorsContainer.innerHTML = '';

                                for (let i = 1; i <= noOfGuarantors; i++) {
                                    guarantorsContainer.innerHTML += `
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Guarantor ${i} Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Guarantor ${i}</label>
                                            <select name="guarantor_ids[]" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                                <option value="">Select a member</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}">{{ $member->surname }} {{ $member->firstname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            `;
                                }

                                // Update application fee section
                                if (applicationFee && applicationFee > 0) {
                                    const formattedFee = new Intl.NumberFormat('en-NG', {
                                        style: 'currency',
                                        currency: 'NGN',
                                        minimumFractionDigits: 2
                                    }).format(applicationFee).replace('NGN', '₦');

                                    applicationFeeText.textContent = `I agree to pay the application fee of ${formattedFee} if my loan is approved, before the loan amount is disbursed.`;
                                    applicationFeeContainer.classList.remove('hidden');
                                    applicationFeeCheckbox.required = true;
                                } else {
                                    applicationFeeContainer.classList.add('hidden');
                                    applicationFeeCheckbox.required = false;
                                }

                                // Update amount constraints
                                if (minAmount && maxAmount) {
                                    loanAmountInput.min = minAmount;
                                    loanAmountInput.max = maxAmount;

                                    const formattedMin = new Intl.NumberFormat('en-NG', {
                                        style: 'currency',
                                        currency: 'NGN',
                                        minimumFractionDigits: 2
                                    }).format(minAmount).replace('NGN', '₦');

                                    const formattedMax = new Intl.NumberFormat('en-NG', {
                                        style: 'currency',
                                        currency: 'NGN',
                                        minimumFractionDigits: 2
                                    }).format(maxAmount).replace('NGN', '₦');

                                    amountRangeText.textContent = `Amount must be between ${formattedMin} and ${formattedMax}`;
                                    amountRangeText.classList.remove('text-red-500');
                                    amountRangeText.classList.add('text-gray-500');
                                }

                                // Update duration constraints
                                if (maxDuration) {
                                    loanDurationInput.min = 1;
                                    loanDurationInput.max = maxDuration;
                                    durationLimitText.textContent = `Maximum duration: ${maxDuration} months`;
                                    durationLimitText.classList.remove('text-red-500');
                                    durationLimitText.classList.add('text-gray-500');
                                }
                            });

                            // Validate amount on input
                            loanAmountInput.addEventListener('input', function() {
                                const selectedOption = loanTypeSelect.options[loanTypeSelect.selectedIndex];
                                if (!selectedOption.value) return;

                                const minAmount = parseFloat(selectedOption.dataset.minAmount);
                                const maxAmount = parseFloat(selectedOption.dataset.maxAmount);
                                const currentAmount = parseFloat(this.value);

                                if (currentAmount < minAmount || currentAmount > maxAmount) {
                                    amountRangeText.classList.remove('text-gray-500');
                                    amountRangeText.classList.add('text-red-500');
                                } else {
                                    amountRangeText.classList.remove('text-red-500');
                                    amountRangeText.classList.add('text-gray-500');
                                }
                            });

                            // Validate duration on input
                            loanDurationInput.addEventListener('input', function() {
                                const selectedOption = loanTypeSelect.options[loanTypeSelect.selectedIndex];
                                if (!selectedOption.value) return;

                                const maxDuration = parseInt(selectedOption.dataset.maxDuration);
                                const currentDuration = parseInt(this.value);

                                if (currentDuration < 1 || currentDuration > maxDuration) {
                                    durationLimitText.classList.remove('text-gray-500');
                                    durationLimitText.classList.add('text-red-500');
                                } else {
                                    durationLimitText.classList.remove('text-red-500');
                                    durationLimitText.classList.add('text-gray-500');
                                }
                            });
                        });
                    </script>
                    <a href="{{ route('member.loans.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('loanAmount').addEventListener('input', function(e) {
        const amount = this.value;

        // Format to thousand separator
        const formatted = new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN'
        }).format(amount);

        document.getElementById('formattedAmount').textContent = formatted;

        // Convert to words
        const inWords = numberToWords(amount);
        document.getElementById('amountInWords').textContent = inWords;
    });

    function numberToWords(number) {
        const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if (number === 0) return 'Zero Naira';

        function convertGroup(n) {
            let result = '';

            if (n >= 100) {
                result += units[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }

            if (n >= 20) {
                result += tens[Math.floor(n / 10)] + ' ';
                n %= 10;
            } else if (n >= 10) {
                result += teens[n - 10] + ' ';
                return result;
            }

            if (n > 0) {
                result += units[n] + ' ';
            }

            return result;
        }

        let result = '';
        let billion = Math.floor(number / 1000000000);
        let million = Math.floor((number % 1000000000) / 1000000);
        let thousand = Math.floor((number % 1000000) / 1000);
        let remainder = number % 1000;

             if (billion) result += convertGroup(billion) + 'Billion ';
        if (million) result += convertGroup(million) + 'Million ';
        if (thousand) result += convertGroup(thousand) + 'Thousand ';
        if (remainder) result += convertGroup(remainder);

        return result + 'Naira';
    }
</script>
@endsection
