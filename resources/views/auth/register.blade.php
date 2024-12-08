@extends('layouts.app')
@section('title', 'Register | OGITECH Cooperative')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-purple-50 to-purple-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <!-- <img src="{{ asset('images/logo.png') }}" alt="OGITECH COOP" class="h-24 mx-auto mb-6"> -->
            <h2 class="text-4xl font-bold text-purple-800 mb-3">Join OGITECH COOP</h2>
            <p class="text-lg text-gray-600">Start your journey towards financial growth</p>
        </div>

        <div class="max-w-5xl mx-auto">
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
                <!-- Progress Steps -->
                <div class="bg-purple-600 px-8 py-6">
                    <!-- ... existing progress steps code ... -->
                </div>
                <!-- Error Messages -->
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-8 mt-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-8 mt-6" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-8 mt-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif
              

                <!-- Form Content -->
                <div class="p-8">
                    <form method="POST" action="{{ route('register.step', ['stage' => $stage]) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Form Fields -->
                        <div class="space-y-8">
                            @include("auth.register.{$stage}")
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between mt-12">
                            @if($stage != 'personal')
                            <button type="button" onclick="window.history.back()" class="flex items-center px-8 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300 transform hover:-translate-x-1">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            @else
                            <div></div>
                            @endif

                            <button type="submit" class="flex items-center px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 transform hover:translate-x-1">
                                {{ $stage == 'documents' ? 'Complete Registration' : 'Continue' }}
                                @if($stage != 'documents')
                                <i class="fas fa-arrow-right ml-2"></i>
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">Need assistance? Contact our support team at <span class="text-purple-600 font-medium">support@ogitechcoop.com</span></p>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    // Dynamic form handling scripts
    document.addEventListener('DOMContentLoaded', function() {
        // State-LGA Dependency
        const stateSelect = document.querySelector('select[name="state_id"]');
        const lgaSelect = document.querySelector('select[name="lga_id"]');
        if (stateSelect && lgaSelect) {
            stateSelect.addEventListener('change', function() {
                fetch(`/api/states/${this.value}/lgas`)
                    .then(response => response.json())
                    .then(data => {
                        lgaSelect.innerHTML = '<option value="">Select LGA</option>';
                        data.forEach(lga => {
                            lgaSelect.innerHTML += `<option value="${lga.id}">${lga.name}</option>`;
                        });
                    });
            });
        }

        // Faculty-Department Dependency
        const facultySelect = document.querySelector('select[name="faculty_id"]');
        const departmentSelect = document.querySelector('select[name="department_id"]');
        if (facultySelect && departmentSelect) {
            facultySelect.addEventListener('change', function() {
                fetch(`/api/faculties/${this.value}/departments`)
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
@endpush
@endsection
