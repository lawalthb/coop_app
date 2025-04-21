@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Loan Type</h2>
            </div>
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
            <form action="{{ route('admin.loan-types.update', $loanType) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ $loanType->name }}" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" value="{{ $loanType->interest_rate }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maximum Duration (Months)</label>
                            <input type="number" name="duration_months" value="{{ $loanType->duration_months }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                            <input type="number" name="minimum_amount" value="{{ $loanType->minimum_amount }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maximum Amount</label>
                            <input type="number" name="maximum_amount" value="{{ $loanType->maximum_amount }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                <option value="active" {{ $loanType->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $loanType->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
        <label class="block text-sm font-medium text-gray-700">Number of Guarantors</label>
        <input type="number" name="no_guarantors" value="{{ $loanType->no_guarantors }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px;">
        @error('no_guarantors')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
      <div>
    <label class="block text-sm font-medium text-gray-700">Application Fee</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 pointer-events-none">₦</span>
    <input
        type="number"
        name="application_fee"
        step="1000"
        min="0"
        value="{{ old('application_fee', $loanType->application_fee ?? 0) }}"
        class="w-full pl-10 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
        required
        style="border: 1px solid #ccc; padding: 10px 10px 10px 30px; font-size: 16px; border-radius: 5px;"
    >
    </div>
    <p class="mt-1 text-sm text-gray-500">Fee charged when applying for this loan type</p>
</div>


                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.loan-types.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Update Loan Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
