@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('member.savings.settings.index') }}" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Settings
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Monthly Savings Amount</h2>
            </div>

            <form action="{{ route('member.savings.settings.update', $setting) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Saving Type</label>
                        <input type="text" value="{{ $setting->savingType->name }}" class="w-full rounded-lg border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₦</span>
                            <input type="number" name="amount" step="0.01" min="0" value="{{ $setting->amount }}" class="w-full pl-8 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                        <input type="text" value="{{ $setting->month->name }}" class="w-full rounded-lg border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <input type="text" value="{{ $setting->year->year }}" class="w-full rounded-lg border-gray-300 bg-gray-100" readonly style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="terms" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" required>
                        </div>
                        <div class="flex-1">
                            <label for="terms" class="font-medium text-gray-700">I understand and agree</label>
                            <p class="text-gray-500 mt-1">
                                I understand that this updated setting will be applied to my monthly savings after admin approval. The specified amount will be deducted from my salary for the selected month and year.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('member.savings.settings.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
