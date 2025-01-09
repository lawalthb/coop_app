@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Import Savings Data</h2>

            <form action="{{ route('admin.savings.process-import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" class="w-full border border-gray-300 rounded-lg p-2">
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-sm text-gray-500">Accepted formats: CSV, Excel (.xlsx, .xls)</p>
                        <a href="{{ route('admin.savings.download-format') }}" class="text-purple-600 hover:text-purple-700">
                            <i class="fas fa-download mr-1"></i>Download Import Format
                        </a>
                    </div>
                </div>
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-upload mr-2"></i>Upload and Process
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
