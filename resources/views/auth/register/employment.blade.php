<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
        <input type="text" name="staff_no" value="{{ old('staff_no') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">SCHOOL/DIRECTORATE/CENTRE</label>
        <select name="faculty_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select Faculty</option>
            @foreach($faculties as $faculty)
            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">DEPARTMENT/UNIT</label>
        <select name="department_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select Department</option>
            <option value="47">None</option>
        </select>
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
                        departmentSelect.innerHTML = '<option value="">Select Department</option><option value="47">None</option>';
                        data.forEach(department => {
                            departmentSelect.innerHTML += `<option value="${department.id}">${department.name}</option>`;
                        });
                    });
            });
        }
    });
</script>
