@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Members Report</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.members.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
                <a href="{{ route('admin.reports.members.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
                <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
            </div>
        </div>

        <!-- Collapsible Filters -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
            <button id="filter-toggle" class="w-full px-6 py-4 bg-gray-50 text-left flex justify-between items-center border-b">
                <div class="flex items-center">
                    <i class="fas fa-filter text-purple-600 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Filter Options</h2>
                </div>
                <div class="flex items-center text-gray-500">
                    <span id="filter-count" class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                        {{ array_filter(request()->only(['status', 'date_from', 'date_to', 'department_id'])) ? count(array_filter(request()->only(['status', 'date_from', 'date_to', 'department_id']))) : '0' }} active
                    </span>
                    <i id="filter-icon" class="fas fa-chevron-down transition-transform duration-300"></i>
                </div>
            </button>

            <div id="filter-panel" class="p-6 {{ array_filter(request()->only(['status', 'date_from', 'date_to', 'department_id'])) ? '' : 'hidden' }}">
                <form action="{{ route('admin.reports.members') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department/Unit</label>
                        <select name="department_id" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 py-2 px-3">
                            <option value="">All Departments</option>
                            @foreach(\App\Models\Department::orderBy('name')->get() as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex-1 flex items-center justify-center border border-purple-600">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.reports.members') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center border border-gray-300">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Total Members</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $members->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Active Members</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $members->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-user-times text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Inactive Members</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $members->where('status', 'inactive')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-transform hover:scale-105 duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Average Savings</h3>
                        <p class="text-3xl font-bold text-blue-600">₦{{ number_format($members->avg('monthly_savings') ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Members List</h2>
                <p class="text-sm text-gray-600">Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} of {{ $members->total() ?? 0 }} members</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shares</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loans</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->member_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($member->member_image)
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $member->member_image) }}" alt="{{ $member->surname }} {{ $member->firstname }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->surname }} {{ $member->firstname }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->othername }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="mailto:{{ $member->email }}" class="text-blue-600 hover:text-blue-900">{{ $member->email }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $member->department->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($member->status === 'inactive' ? 'bg-red-100 text-red-800' :
                                       ($member->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $member->shares_count }}</span>
                                    @if($member->shares_count > 0)
                                    <span class="ml-2 text-xs text-green-600">(₦{{ number_format($member->shares_value ?? 0, 2) }})</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $member->loans_count }}</span>
                                    @if($member->active_loans_count > 0)
                                    <span class="ml-2 text-xs text-blue-600">({{ $member->active_loans_count }} active)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.members.show', $member) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('members.pdf', $member) }}" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                No members found matching the selected criteria.
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
        // Toggle filter panel
        const filterToggle = document.getElementById('filter-toggle');
        const filterPanel = document.getElementById('filter-panel');
        const filterIcon = document.getElementById('filter-icon');

        filterToggle.addEventListener('click', function() {
            filterPanel.classList.toggle('hidden');
            filterIcon.classList.toggle('transform');
            filterIcon.classList.toggle('rotate-180');
        });

        // Department filter based on faculty selection
        const facultySelect = document.querySelector('select[name="faculty_id"]');
        const departmentSelect = document.querySelector('select[name="department_id"]');

        if (facultySelect && departmentSelect) {
            facultySelect.addEventListener('change', function() {
                const facultyId = this.value;

                // Clear current departments
                departmentSelect.innerHTML = '<option value="">All Departments</option>';

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
        }

        // Date range validation
        const dateFrom = document.querySelector('input[name="date_from"]');
        const dateTo = document.querySelector('input[name="date_to"]');

        if (dateFrom && dateTo) {
            dateFrom.addEventListener('change', function() {
                dateTo.min = this.value;
                if (dateTo.value && dateTo.value < this.value) {
                    dateTo.value = this.value;
                }
            });

            dateTo.addEventListener('change', function() {
                dateFrom.max = this.value;
                if (dateFrom.value && dateFrom.value > this.value) {
                    dateFrom.value = this.value;
                }
            });
        }
    });
</script>
<style>
    @media print {
        .bg-purple-50 { background-color: white !important; }
        .shadow-lg { box-shadow: none !important; }
        .rounded-xl { border-radius: 0 !important; }
        .bg-white { background-color: white !important; }

        button, a.bg-green-600, a.bg-red-600, button.bg-purple-600,
        form button[type="submit"], .pagination, .flex.space-x-3 {
            display: none !important;
        }

        .min-h-screen { min-height: auto !important; }

        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
    }
</style>
@endsection

