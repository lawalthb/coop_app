@extends(auth()->user()->is_admin ? 'layouts.admin' : 'layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Profile</h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @csrf
                @method('PUT')

                <!-- Profile Image Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                    <div class="flex items-center space-x-6">
                        <img src="{{ asset('storage/' . auth()->user()->member_image) }}"
                            alt="Profile" class="w-24 h-24 rounded-full object-cover">
                        <input type="file" name="profile_image" accept="image/*" class="text-sm">
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <select name="title" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            @foreach(['Arc.', 'Bldr.', 'Dr.', 'Engr.', 'Mr.', 'Mrs.', 'Ms.', 'Pharm.', 'Prof.', 'Pst.', 'Rev.'] as $title)
                            <option value="{{ $title }}" {{ $user->title === $title ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                        <input type="text" name="surname" value="{{ $user->surname }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="firstname" value="{{ $user->firstname }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Other Name</label>
                        <input type="text" name="othername" value="{{ $user->othername }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <select name="religion" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            <option value="">Select Religion</option>
                            <option value="Christianity" {{ $user->religion === 'Christianity' ? 'selected' : '' }}>Christianity</option>
                            <option value="Islam" {{ $user->religion === 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Others" {{ $user->religion === 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                        <select name="marital_status" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
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
                        <input type="tel" name="phone_number" value="{{ $user->phone_number }}" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" class="w-full rounded-lg border-gray-300" disabled style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Home Address</label>
                        <textarea name="home_address" rows="3" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">{{ $user->home_address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select name="state_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $user->state_id === $state->id ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                        <select name="lga_id" class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            <!-- Will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Save Changes
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



@endsection
