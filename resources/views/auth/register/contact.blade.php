<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Home Address (Full Residential Address)</label>
        <textarea name="home_address" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">{{ old('home_address') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;" placeholder="This will be used for login">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">State of Origin</label>
        <select name="state_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select State</option>
            @foreach($states as $state)
            <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">LGA of Origin</label>
        <select name="lga_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            <option value="">Select LGA</option>
        </select>
    </div>
</div>


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
