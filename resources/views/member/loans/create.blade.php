@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Application</h2>
            </div>

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
                        <input type="number" name="amount" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
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

                        for(let i = 1; i <= noOfGuarantors; i++) {
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
@endsection