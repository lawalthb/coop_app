<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
        <input type="text" name="staff_no" value="{{ old('staff_no') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required   style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
        <select name="faculty_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required  style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select Faculty</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
        <select name="department_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select Department</option>
        </select>
    </div>
</div>
