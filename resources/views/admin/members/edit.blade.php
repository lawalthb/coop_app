@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Member Details</h2>

        <form action="{{ route('admin.members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <select name="title" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        @foreach(['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.'] as $title)
                        <option value="{{ $title }}" {{ $member->title == $title ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" name="firstname" value="{{ $member->firstname }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                    <input type="text" name="surname" value="{{ $member->surname }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $member->email }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ $member->phone_number }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
                    <input type="text" name="staff_no" value="{{ $member->staff_no }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                    <select name="faculty_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ $member->faculty_id == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ $member->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Savings</label>
                    <input type="number" name="monthly_savings" value="{{ $member->monthly_savings }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Share Subscription</label>
                    <input type="number" name="share_subscription" value="{{ $member->share_subscription }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.members.show', $member) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-4">Cancel</a>
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg">Update Member</button>
            </div>
        </form>
    </div>
</div>
@endsection
