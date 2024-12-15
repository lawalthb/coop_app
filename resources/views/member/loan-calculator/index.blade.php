@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Check Your Loan Eligibility</h2>
            </div>

            <div class="p-6">
                <form action="{{ route('member.loan-calculator.check') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="loan_type_id" class="block text-sm font-medium text-gray-700">Loan Type</label>
                        <select name="loan_type_id" id="loan_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">Select loan type</option>
                            @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Loan Amount</label>
                        <input type="number" name="amount" id="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Duration (Months)</label>
                        <input type="number" name="duration" id="duration" min="1" max="18" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Check Eligibility
                        </button>
                    </div>
                </form>
                <!-- Results Section -->
                <div id="results" class="mt-8 hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Results</h3>
                    <div id="eligibilityStatus" class="mb-4"></div>
                    <div id="loanDetails" class="space-y-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('calculatorForm').addEventListener('submit', function(e) {
        e.preventDefault();

        fetch('{{ route("member.loan-calculator.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(new FormData(this)))
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('results');
                const statusDiv = document.getElementById('eligibilityStatus');
                const detailsDiv = document.getElementById('loanDetails');

                resultsDiv.classList.remove('hidden');

                statusDiv.innerHTML = `
            <div class="p-4 rounded-lg ${data.eligible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                <p class="font-medium">${data.eligible ? 'You are eligible for this loan' : 'You are not eligible for this loan'}</p>
                ${data.messages.map(msg => `<p class="text-sm mt-1">${msg}</p>`).join('')}
            </div>
        `;

                if (data.loan_details) {
                    detailsDiv.innerHTML = `
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Principal Amount</p>
                            <p class="font-medium">₦${numberFormat(data.loan_details.principal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Interest Rate</p>
                            <p class="font-medium">${data.loan_details.interest_rate}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Interest</p>
                            <p class="font-medium">₦${numberFormat(data.loan_details.total_interest)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="font-medium">₦${numberFormat(data.loan_details.total_amount)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Monthly Repayment</p>
                            <p class="font-medium">₦${numberFormat(data.loan_details.monthly_repayment)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-medium">${data.loan_details.duration} months</p>
                        </div>
                    </div>
                </div>
            `;
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function numberFormat(number) {
        return new Intl.NumberFormat().format(number.toFixed(2));
    }
</script>

@endsection