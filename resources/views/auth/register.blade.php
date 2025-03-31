@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-purple-50 py-12">

    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->

            <div class="bg-purple-600 px-8 py-6 mt-5">
                <h2 class="text-2xl font-bold text-white">OGITECH COOP Registration</h2>
                <p class="text-purple-100 mt-1">Complete all steps to become a member</p>
            </div>

            <!-- Progress Steps -->
            <div class="px-8 py-4 bg-white border-b">
                <div class="flex justify-between mb-3">
                    @foreach(['Personal', 'Contact', 'Employment', 'Next of Kin', 'Financial', 'Documents'] as $stepName)
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $stage == strtolower(str_replace(' ', '_', $stepName)) ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            {{ $loop->iteration }}
                        </div>
                        <span class="text-sm mt-1 {{ $stage == strtolower(str_replace(' ', '_', $stepName)) ? 'text-purple-600 font-semibold' : 'text-gray-500' }}">
                            {{ $stepName }}
                        </span>
                    </div>
                    @if(!$loop->last)
                    <div class="flex-1 flex items-center mx-4">
                        <div class="h-1 w-full bg-gray-200 rounded">
                            <div class="h-1 bg-purple-600 rounded" style="width: {{ $stage == strtolower(str_replace(' ', '_', $stepName)) ? '100%' : '0%' }}"></div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" style="color: red;">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" style="color: red;">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>

            <!-- Form Content -->
            <form style="margin: 20px;" method="POST" action="{{ route('register.step', ['stage' => $stage]) }}" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                @if($stage == 'personal')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <select name="title" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            @foreach(['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.', 'Surv.', 'Qs.', 'Tpl.', 'Esv.'] as $title)
                            <option value="{{ $title }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                        <input type="text" name="surname" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="firstname" class="block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div class="mb-5">
                        <label for="othername" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Othername</label>
                        <input type="text" id="othername" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Optional" style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="dob" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <input type="text" value="Nigerian" name="nationality" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                </div>

                @elseif($stage == 'contact')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Home Address (Full Residential Address)</label>
                        <textarea name="home_address" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;" placeholder="house number, street , bus stop, road, lga, state"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone_number" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;" placeholder="will be used for login">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State of Origin</label>
                        <select name="state_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                        <select name="lga_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <!-- Will be populated via JavaScript -->
                            <option value="">Select LGA</option>
                        </select>
                    </div>
                </div>

                @elseif($stage == 'employment')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
                        <input type="text" name="staff_no" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SCHOOL/DIRECTORATE/CENTRE</label>
                        <select name="faculty_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DEPARTMENT/UNIT</label>
                        <select name="department_id" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <!-- Will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                @elseif($stage == 'next_of_kin')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Full Name</label>
                        <input type="text" name="nok" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                        <input type="text" name="nok_relationship" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Address</label>
                        <textarea name="nok_address" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Phone</label>
                        <input type="tel" name="nok_phone" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                </div>

                @elseif($stage == 'financial')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Savings Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₦</span>
                            <input type="number" name="monthly_savings" value="{{ old('monthly_savings') }}" class="w-full pl-8 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding-top: 10px;  padding-bottom: 10px; font-size: 16px; border-radius: 5px;">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share Subscription Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₦</span>
                            <input type="number" name="share_subscription" value="{{ old('share_subscription') }}" class="w-full pl-8 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;padding-top: 10px;  padding-bottom: 10px;  font-size: 16px; border-radius: 5px;">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month to Commence</label>
                        <input type="month" name="month_commence" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                </div>

                @elseif($stage == 'documents')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                        <input type="file" name="member_image" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" accept="image/*" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Signature</label>
                        <input type="file" name="signature_image" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" accept="image/*" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
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
                    @endif

                    <div class="mt-8 flex justify-between">
                        @if($stage != 'personal')
                        <button type="button" onclick="window.history.back()" class="bg-slate-600-600 text-white px-6 py-2 rounded hover:bg-slate-700" style="margin: 20px; background-color: grey;"> Previous</button>
                        @else
                        <div></div>
                        @endif
                        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700" style="margin: 20px;">
                            {{ $stage == 'documents' ? 'Complete Registration' : 'Next' }}
                        </button>
                    </div>
            </form>
        </div>
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
        const salaryCheckbox = document.getElementById('salary_deduction');
        const membershipCheckbox = document.getElementById('membership_declaration');
        const submitButton = document.querySelector('button[type="submit"]');
        const currentStage = "{{ $stage }}"; // Get the current stage from PHP

        // Only apply checkbox validation on the documents stage
        if (currentStage === 'documents') {
            function checkBoxesStatus() {
                if (salaryCheckbox.checked && membershipCheckbox.checked) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-purple-700');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-purple-700');
                }
            }

            // Initially disable the button
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');

            // Add event listeners to both checkboxes
            salaryCheckbox.addEventListener('change', checkBoxesStatus);
            membershipCheckbox.addEventListener('change', checkBoxesStatus);
        }
    });
</script>

@endsection
