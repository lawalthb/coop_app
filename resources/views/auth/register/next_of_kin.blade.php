<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Full Name</label>
        <input type="text" name="nok" value="{{ old('nok') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
        <input type="text" name="nok_relationship" value="{{ old('nok_relationship') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Address</label>
        <textarea name="nok_address" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">{{ old('nok_address') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Phone Number</label>
        <input type="tel" name="nok_phone" value="{{ old('nok_phone') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>
</div>