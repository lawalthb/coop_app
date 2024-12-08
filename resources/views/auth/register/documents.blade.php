<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
        <input type="file" name="member_image" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" accept="image/*" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
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

<!-- Declarations Section -->
<div class="mt-8 space-y-6">
    <!-- Salary Deduction Declaration -->
    <div class="bg-gray-50 p-6 rounded-lg">
        <div class="flex items-start space-x-3">
            <div class="flex items-center h-5">
                <input type="checkbox" name="salary_deduction_agreement" id="salary_deduction" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" required>
            </div>
            <div class="flex-1">
                <label for="salary_deduction" class="font-medium text-gray-700">AUTHORITY TO DEDUCT FROM SALARY</label>
                <p class="text-gray-500 mt-1">
                    Please deduct from my monthly salary the sum as stated on my application form and as amended from time to time either via official communication or via other forms of communication. To be credited in my account with OGITECH Academic Staff Cooperative multipurpose Society with effect from the month of application.
                </p>
            </div>
        </div>
    </div>

    <!-- Membership Declaration -->
    <div class="bg-gray-50 p-6 rounded-lg">
        <div class="flex items-start space-x-3">
            <div class="flex items-center h-5">
                <input type="checkbox" name="membership_declaration" id="membership_declaration" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" required>
            </div>
            <div class="flex-1">
                <label for="membership_declaration" class="font-medium text-gray-700">DECLARATION</label>
                <p class="text-gray-500 mt-1">
                    I hereby apply for enrolment as member of the above-named Society and promised to abide by the rules and regulations as contained in the bye-laws and as amended from time to time and any other provisions as the case may be.
                </p>
                <p class="text-gray-500 mt-2">
                    I do solemnly declare that the information provided above, in this application, is true and correct.
                </p>
            </div>
        </div>
    </div>
</div>
