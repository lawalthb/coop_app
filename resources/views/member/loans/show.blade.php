@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-purple-600">
            <h2 class="text-xl font-semibold text-white">Loan Details</h2>
        </div>

        <div class="p-6">
            <!-- Loan Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Loan Information</h3>
                    <div class="space-y-3">
                        <p><span class="text-gray-600">Reference:</span> {{ $loan->reference }}</p>
                        <p><span class="text-gray-600">Type:</span> {{ $loan->loanType->name }}</p>
                        <p><span class="text-gray-600">Amount:</span> ₦{{ number_format($loan->amount, 2) }}</p>
                        <p><span class="text-gray-600">Interest:</span> ₦{{ number_format($loan->interest_amount, 2) }}</p>
                        <p><span class="text-gray-600">Total Amount:</span> ₦{{ number_format($loan->total_amount, 2) }}</p>
                        <p><span class="text-gray-600">Monthly Payment:</span> ₦{{ number_format($loan->monthly_payment, 2) }}</p>
                        <p><span class="text-gray-600">Duration:</span> {{ $loan->duration }} months</p>
                        <p><span class="text-gray-600">Start Date:</span> {{ $loan->start_date->format('M d, Y') }}</p>
                        <p><span class="text-gray-600">End Date:</span> {{ $loan->end_date->format('M d, Y') }}</p>
                        <p><span class="text-gray-600">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($loan->status === 'active') bg-green-100 text-green-800
                                @elseif($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($loan->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </p>
                    </div>
                </div>










                  <div>
                      <h3 class="text-lg font-semibold text-gray-800 mb-4">Guarantor Information</h3>
                      <div class="overflow-x-auto">
                          <table class="min-w-full divide-y divide-gray-200">
                              <thead class="bg-gray-50">
                                  <tr>
                                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Updated</th>
                                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                  </tr>
                              </thead>
                              <tbody class="bg-white divide-y divide-gray-200">
                                  @forelse($loan->guarantors as $guarantor)
                                      <tr>
                                          <td class="px-6 py-4">{{ $guarantor->user->surname }} {{ $guarantor->user->firstname }}</td>
                                          <td class="px-6 py-4">
                                              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                  {{ $guarantor->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                   ($guarantor->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                  {{ ucfirst($guarantor->status) }}
                                              </span>
                                          </td>
                                          <td class="px-6 py-4">{{ $guarantor->updated_at->format('M d, Y H:i') }}</td>
                                          <td class="px-6 py-4">{{ $guarantor->remarks }}</td>
                                      </tr>
                                  @empty
                                      <tr>
                                          <td colspan="4" class="px-6 py-4 text-center text-gray-500">No guarantors found</td>
                                      </tr>
                                  @endforelse
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>

            <!-- Repayment History -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Repayment History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($repayments as $repayment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $repayment->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ₦{{ number_format($repayment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $repayment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($repayment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No repayments found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
