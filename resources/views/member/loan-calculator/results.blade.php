@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Eligibility Results</h2>
            </div>

            <div class="p-6">
                <!-- Eligibility Status -->
                <div class="mb-6">
                    <div class="p-4 rounded-lg {{ $eligibility['eligible'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <p class="font-medium">{{ $eligibility['eligible'] ? 'You are eligible for this loan!' : 'You are not eligible for this loan.' }}</p>
                        @foreach($eligibility['messages'] as $message)
                            <p class="text-sm mt-1">{{ $message }}</p>
                        @endforeach
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Loan Details</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Principal Amount</p>
                            <p class="font-medium">₦{{ number_format($loanDetails['principal'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Interest Rate</p>
                            <p class="font-medium">{{ $loanDetails['interest_rate'] }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Interest</p>
                            <p class="font-medium">₦{{ number_format($loanDetails['total_interest'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Amount</p>
                            <p class="font-medium">₦{{ number_format($loanDetails['total_amount'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Monthly Repayment</p>
                            <p class="font-medium">₦{{ number_format($loanDetails['monthly_repayment'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-medium">{{ $loanDetails['duration'] }} months</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('member.loan-calculator') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Calculate Another
                    </a>
                    @if($eligibility['eligible'])
                    <a href="{{ route('member.loans.create', ['type' => $loanType->id]) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Apply for This Loan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
