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
                        <select name="loan_type_id" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                            <option value="">Select Loan Type</option>
                            @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
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

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Guarantor Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Guarantor Name</label>
                            <input type="text" name="guarantor_name" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Guarantor Phone</label>
                            <input type="tel" name="guarantor_phone" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Guarantor Address</label>
                            <textarea name="guarantor_address" rows="2" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; outline: none; transition: border-color 0.3s ease;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
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