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
                            <option value="{{ $type->id }}" data-guarantors="{{ $type->no_guarantors }}">{{ $type->name }}</option>
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
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration (months)</label>
                        <input type="number" name="duration" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purpose of Loan</label>
                    <textarea name="purpose" rows="3" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;"></textarea>
                </div>

                <!-- Add this container for dynamic guarantor fields -->
                <div id="guarantorsContainer"></div>

                <div class="flex justify-end space-x-4">

                    <!-- Add this JavaScript at the top of the form -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const loanTypeSelect = document.getElementById('loan_type_id');
                            const guarantorsContainer = document.getElementById('guarantorsContainer');

                            loanTypeSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const noOfGuarantors = selectedOption.dataset.guarantors;

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
