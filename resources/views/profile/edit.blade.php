@extends(auth()->user()->is_admin ? 'layouts.admin' : 'layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Request Profile Update</h2>
            </div>

            <form action="{{ route('profile.request-update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <!-- Profile & Signature Images -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                        <div class="flex items-center space-x-6">
                            <img src="{{ asset('storage/' . auth()->user()->member_image) }}"
                                alt="Profile" class="w-24 h-24 rounded-full object-cover">
                            <input type="file" name="member_image" accept="image/*" class="text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Signature Image</label>
                        <div class="flex items-center space-x-6">
                            <img src="{{ asset('storage/' . auth()->user()->signature_image) }}"
                                alt="Signature" class="w-24 h-12 object-contain">
                            <input type="file" name="signature_image" accept="image/*" class="text-sm">
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <select name="title" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            @foreach(['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.', 'Surv.', 'Qs.', 'Tpl.', 'Esv.'] as $title)
                            <option value="{{ $title }}" {{ $user->title === $title ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Staff Number</label>
                        <input type="text" name="staff_no" value="{{ $user->staff_no }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Joined</label>
                        <input type="date" name="date_join" value="{{ $user->date_join }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                        <input type="text" name="surname" value="{{ $user->surname }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="firstname" value="{{ $user->firstname }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Other Name</label>
                        <input type="text" name="othername" value="{{ $user->othername }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="dob" value="{{ $user->dob }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <input type="text" name="nationality" value="{{ $user->nationality }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <select name="religion" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            <option value="">Select Religion</option>
                            <option value="Christianity" {{ $user->religion === 'Christianity' ? 'selected' : '' }}>Christianity</option>
                            <option value="Islam" {{ $user->religion === 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Others" {{ $user->religion === 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                        <select name="marital_status" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            <option value="">Select Marital Status</option>
                            <option value="Single" {{ $user->marital_status === 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ $user->marital_status === 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ $user->marital_status === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ $user->marital_status === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone_number" value="{{ $user->phone_number }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Home Address</label>
                        <textarea name="home_address" rows="3" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">{{ $user->home_address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select name="state_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $user->state_id === $state->id ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                        <select name="lga_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            <!-- Will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                        <select name="faculty_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ $user->faculty_id === $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select name="department_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                            <!-- Will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                <!-- Next of Kin Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin</label>
                        <input type="text" name="nok" value="{{ $user->nok }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship with Next of Kin</label>
                        <input type="text" name="nok_relationship" value="{{ $user->nok_relationship }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Phone</label>
                        <input type="tel" name="nok_phone" value="{{ $user->nok_phone }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next of Kin Address</label>
                        <textarea name="nok_address" rows="3" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">{{ $user->nok_address }}</textarea>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Savings</label>
                        <input type="number" name="monthly_savings" value="{{ $user->monthly_savings }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share Subscription</label>
                        <input type="number" name="share_subscription" value="{{ $user->share_subscription }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Month to Commence</label>
                        <input type="month" name="month_commence" value="{{ $user->month_commence }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 5px">
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    @if(auth()->user()->profileUpdateRequests()->where('status', 'pending')->exists())
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
                        <p class="font-bold">Profile Update Request Pending</p>
                        <p>Your profile update request is currently under review by admin. You'll be notified once processed.</p>
                    </div>
                    @else
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Submit Update Request
                    </button>
                    @endif
                </div>
        </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
             
                const stateSelect = document.querySelector('select[name="state_id"]');
                const lgaSelect = document.querySelector('select[name="lga_id"]');
                const facultySelect = document.querySelector('select[name="faculty_id"]');
                const departmentSelect = document.querySelector('select[name="department_id"]');

                if (stateSelect && lgaSelect) {
                    stateSelect.addEventListener('change', function() {
                        fetch(`/states/${this.value}/lgas`)
                            .then(response => response.json())
                            .then(data => {
                                lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                                data.forEach(lga => {
                                    lgaSelect.innerHTML += `<option value="${lga.id}">${lga.name}</option>`;
                                });
                            });
                    });
                }

                if (facultySelect && departmentSelect) {
                    facultySelect.addEventListener('change', function() {
                        fetch(`/faculties/${this.value}/departments`)
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
    </div>
</div>
</div>

@push('scripts')


@endpush

@endsection
