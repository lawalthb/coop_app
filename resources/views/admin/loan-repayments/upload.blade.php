@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Upload Loan Repayments</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.loans.repayments.download-template') }}"
                           class="inline-flex items-center px-3 py-2 border border-white text-sm font-medium rounded-md text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Download Template
                        </a>
                        <a href="{{ route('admin.loans.repayments.index') }}"
                           class="inline-flex items-center px-3 py-2 border border-white text-sm font-medium rounded-md text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Repayments
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('upload_errors') && count(session('upload_errors')) > 0)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <h4 class="font-semibold mb-2">Upload Errors:</h4>
                        <ul class="list-disc list-inside text-sm max-h-40 overflow-y-auto">
                            @foreach(session('upload_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Upload Instructions</h3>
                    <div class="text-sm text-blue-800">
                        <p class="mb-2">Please prepare your Excel/CSV file with the following columns:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li><strong>Column A:</strong> Member Email</li>
                            <li><strong>Column B:</strong> Loan Reference</li>
                            <li><strong>Column C:</strong> Payment Amount</li>
                            <li><strong>Column D:</strong> Month ID</li>
                            <li><strong>Column E:</strong> Year ID</li>
                        </ol>
                        <p class="mt-3 text-xs">
                            <strong>Note:</strong> The first row can be headers (they will be ignored).
                            Supported formats: .xlsx, .xls, .csv (Max size: 2MB)
                        </p>
                        <p class="mt-2 text-xs">
                            <strong>Tip:</strong> Use the "Download Template" button to get a pre-filled Excel file with all active loans.
                        </p>
                    </div>
                </div>

                <!-- Month/Year Reference -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Month & Year Reference</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Available Months:</h5>
                            <div class="text-xs text-gray-600 space-y-1">
                                @foreach($months as $month)
                                    <div>ID: {{ $month->id }} - {{ $month->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Available Years:</h5>
                            <div class="text-xs text-gray-600 space-y-1">
                                @foreach($years as $year)
                                    <div>ID: {{ $year->id }} - {{ $year->year }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Format -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Sample Format:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">Email</th>
                                    <th class="px-3 py-2 text-left">Loan Reference</th>
                                    <th class="px-3 py-2 text-left">Amount</th>
                                    <th class="px-3 py-2 text-left">Month ID</th>
                                    <th class="px-3 py-2 text-left">Year ID</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-t">
                                    <td class="px-3 py-2">john@example.com</td>
                                    <td class="px-3 py-2">LOAN-2024-ABC123</td>
                                    <td class="px-3 py-2">50000</td>
                                    <td class="px-3 py-2">1</td>
                                    <td class="px-3 py-2">1</td>
                                </tr>
                                <tr class="border-t">
                                    <td class="px-3 py-2">jane@example.com</td>
                                    <td class="px-3 py-2">LOAN-2024-DEF456</td>
                                    <td class="px-3 py-2">25000</td>
                                    <td class="px-3 py-2">2</td>
                                    <td class="px-3 py-2">1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Upload Form -->
                <form action="{{ route('admin.loans.repayments.process-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- File Upload -->
                        <div class="md:col-span-2">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                Select File <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload a file</span>
                                            <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">Excel or CSV up to 2MB</p>
                                </div>
                            </div>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="payment_date"
                                   name="payment_date"
                                   value="{{ old('payment_date', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                   required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_method"
                                    name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="deduction" {{ old('payment_method') == 'deduction' ? 'selected' : '' }}>Salary Deduction</option>
                                <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="pos" {{ old('payment_method') == 'pos' ? 'selected' : '' }}>POS</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Add any additional notes about this bulk upload...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.loans.repayments.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Process Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// File upload preview
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);

        // Update the upload area to show selected file
        const uploadArea = e.target.closest('.border-dashed');
        uploadArea.innerHTML = `
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-gray-600">
                    <p class="font-medium text-purple-600">${fileName}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
                <button type="button" onclick="clearFile()" class="text-xs text-red-600 hover:text-red-800">Remove file</button>
            </div>
        `;
    }
});

function clearFile() {
    document.getElementById('file').value = '';
    location.reload(); // Simple way to reset the upload area
}
</script>
@endsection