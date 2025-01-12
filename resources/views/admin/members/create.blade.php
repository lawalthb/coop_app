@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Add New Member</h2>
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Please correct the following errors:
                        </h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                            <input type="text" name="surname" value="{{ old('surname') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
                        <input type="text" name="staff_no" value="{{ old('staff_no') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                            <select name="faculty_id" id="faculty_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select name="department_id" id="department_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Department</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select name="state_id" id="state_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                            <select name="lga_id" id="lga_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select LGA</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Joined</label>
                            <input type="date" name="date_join" value="{{ old('date_join') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Savings</label>
                            <input type="number" name="monthly_savings" value="{{ old('monthly_savings') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                        <input type="file" name="member_image" accept="image/*"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.members') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Create Member
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const facultySelect = document.querySelector('select[name="faculty_id"]');
        const departmentSelect = document.querySelector('select[name="department_id"]');

        if (facultySelect && departmentSelect) {
            facultySelect.addEventListener('change', function() {
                const facultyId = this.value;
                fetch(`/faculties/${facultyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        departmentSelect.innerHTML = '<option value="">Select Department</option>';
                        data.forEach(department => {
                            departmentSelect.innerHTML += `<option value="${department.id}">${department.name}</option>`;
                        });
                    });
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stateSelect = document.querySelector('select[name="state_id"]');
        const lgaSelect = document.querySelector('select[name="lga_id"]');

        if (stateSelect && lgaSelect) {
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                fetch(`/states/${stateId}/lgas`)
                    .then(response => response.json())
                    .then(data => {
                        lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                        data.forEach(lga => {
                            lgaSelect.innerHTML += `<option value="${lga.id}">${lga.name}</option>`;
                        });
                    });
            });
        }
    });
</script>

@endsection
