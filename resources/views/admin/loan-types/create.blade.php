@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Add New Loan Type</h2>
            </div>

            <form action="{{ route('admin.loan-types.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Loan Type Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name', $loanType->name ?? '') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="required_active_savings_months" class="block text-sm font-medium text-gray-700">Required Savings Months</label>
                            <input type="number" name="required_active_savings_months" id="required_active_savings_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('required_active_savings_months', $loanType->required_active_savings_months ?? 6) }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;" value="10">
                        </div>

                        <div>
                            <label for="savings_multiplier" class="block text-sm font-medium text-gray-700">Savings Multiplier</label>
                            <input type="number" step="0.01" name="savings_multiplier" id="savings_multiplier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('savings_multiplier', $loanType->savings_multiplier ?? 2) }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="interest_rate_12_months" class="block text-sm font-medium text-gray-700">Interest Rate (12 months)</label>
                            <input type="number" step="0.01" name="interest_rate_12_months" id="interest_rate_12_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('interest_rate_12_months', $loanType->interest_rate_12_months ?? '10') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label for="interest_rate_18_months" class="block text-sm font-medium text-gray-700">Interest Rate (18 months)</label>
                            <input type="number" step="0.01" name="interest_rate_18_months" id="interest_rate_18_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('interest_rate_18_months', $loanType->interest_rate_18_months ?? '15') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="minimum_amount" class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                            <input type="number" step="0.01" name="minimum_amount" id="minimum_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('minimum_amount', $loanType->minimum_amount ?? '') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label for="maximum_amount" class="block text-sm font-medium text-gray-700">Maximum Amount</label>
                            <input type="number" step="0.01" name="maximum_amount" id="maximum_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('maximum_amount', $loanType->maximum_amount ?? '') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>


                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="max_duration_months" class="block text-sm font-medium text-gray-700">Maximum Duration (Months)</label>
                            <input type="number" name="max_duration_months" id="max_duration_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('max_duration_months', $loanType->max_duration_months ?? 18) }}" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            @error('max_duration_months')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_guarantors" class="block text-sm font-medium text-gray-700">Number of Guarantors Required</label>
                            <input type="number" name="no_guarantors" id="no_guarantors" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('no_guarantors', $loanType->no_guarantors ?? 2) }}" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            @error('no_guarantors')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>




                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="allow_early_payment" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('allow_early_payment', $loanType->allow_early_payment ?? true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Allow Early Payment</span>
                        </label>
                    </div>



                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Loan Type</button>
                    </div>
                </div>
            </form>
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection