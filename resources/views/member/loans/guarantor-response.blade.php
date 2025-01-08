@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Guarantor Request</h2>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Loan Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Applicant</p>
                            <p class="font-medium">{{ $loan->user->surname }} {{ $loan->user->firstname }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-medium">₦{{ number_format($loan->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-medium">{{ $loan->duration }} months</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Purpose</p>
                            <p class="font-medium">{{ $loan->purpose }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('member.guarantor.respond', $loan) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Your Response</label>
                            <select name="response" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Response</option>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </div>

                        <div id="reasonDiv" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                            <textarea name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                                Submit Response
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('select[name="response"]').addEventListener('change', function() {
        const reasonDiv = document.getElementById('reasonDiv');
        reasonDiv.classList.toggle('hidden', this.value !== 'rejected');
    });
</script>
@endpush
@endsection
