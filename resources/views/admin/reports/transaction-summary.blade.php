@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Transaction Summary Report</h1>
            <div class="flex space-x-3">
                <form action="{{ route('admin.reports.transaction-summary.csv') }}" method="GET" class="inline">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="money_type" value="{{ $moneyType }}">
                    @if($departmentId)
                    <input type="hidden" name="department_id" value="{{ $departmentId }}">
                    @endif
                    @if($facultyId)
                    <input type="hidden" name="faculty_id" value="{{ $facultyId }}">
                    @endif
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-file-excel mr-2"></i>Export CSV
                    </button>
                </form>

                <form action="{{ route('admin.reports.transaction-summary.pdf') }}" method="GET" class="inline">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="money_type" value="{{ $moneyType }}">
                    @if($departmentId)
                    <input type="hidden" name="department_id" value="{{ $departmentId }}">
                    @endif
                    @if($facultyId)
                    <input type="hidden" name="faculty_id" value="{{ $facultyId }}">
                    @endif
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-file-pdf mr-2"></i>Download PDF
                    </button>
                </form>

                          <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Filter Options</h2>
            <form action="{{ route('admin.reports.transaction-summary') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select name="year" id="year" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            @foreach($years as $yearOption)
                                <option value="{{ $yearOption->year }}" {{ $year == $yearOption->year ? 'selected' : '' }}>
                                    {{ $yearOption->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select name="month" id="month" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            @foreach($months as $monthOption)
                                <option value="{{ $monthOption->id }}" {{ $month == $monthOption->id ? 'selected' : '' }}>
                                    {{ $monthOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="money_type" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                        <select name="money_type" id="money_type" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="credit" {{ $moneyType == 'credit' ? 'selected' : '' }}>Credit (Inflow)</option>
                            <option value="debit" {{ $moneyType == 'debit' ? 'selected' : '' }}>Debit (Outflow)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="faculty_id" class="block text-sm font-medium text-gray-700 mb-1">School/Directorate/Centre</label>
                        <select name="faculty_id" id="faculty_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">All Schools/Directorates/Centres</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ $facultyId == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department/Unit</label>
                        <select name="department_id" id="department_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">All Departments/Units</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Total Members</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $members->total() }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Transaction Types</h3>
                <p class="text-3xl font-bold text-purple-600">{{ count($distinctTypes) }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Total Amount</h3>
                <p class="text-3xl font-bold text-purple-600">₦{{ number_format($members->sum('total'), 2) }}</p>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SN</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                            @foreach($distinctTypes as $type)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $index => $member)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $members->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $member->member_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $member->surname }} {{ $member->firstname }} {{ $member->othername }}</td>
                                @foreach($distinctTypes as $type)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ₦{{ number_format($member->transaction_data[$type] ?? 0, 2) }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap font-bold">
                                    ₦{{ number_format($member->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($distinctTypes) }}" class="px-6 py-4 text-center text-gray-500">
                                    No transactions found for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $members->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const facultySelect = document.getElementById('faculty_id');
        const departmentSelect = document.getElementById('department_id');

        facultySelect.addEventListener('change', function() {
            const facultyId = this.value;

            // Clear current departments
            departmentSelect.innerHTML = '<option value="">All Departments/Units</option>';

            if (facultyId) {
                // Fetch departments for selected faculty
                fetch(`/faculties/${facultyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(department => {
                            const option = document.createElement('option');
                            option.value = department.id;
                            option.textContent = department.name;
                            departmentSelect.appendChild(option);
                        });
                    });
            }
        });
    });
</script>
@endsection

