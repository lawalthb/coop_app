<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
        <input type="file" name="member_image" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" accept="image/*" required  style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
        <p class="text-sm text-gray-500 mt-1">Upload a clear passport photograph</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Signature</label>
        <input type="file" name="signature_image" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" accept="image/*" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
        <p class="text-sm text-gray-500 mt-1">Upload your signature image</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
    </div>
</div>
