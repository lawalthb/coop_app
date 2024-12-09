@extends(auth()->user()->is_admin ? 'layouts.admin' : 'layouts.member')


@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">My Profile</h2>
                <a href="{{ route('profile.edit') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50">
                    Edit Profile
                </a>
            </div>

            <div class="p-6">
                <!-- Profile Image and Basic Info -->
                <div class="flex items-start space-x-6 mb-8">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . auth()->user()->member_image) }}"
                            alt=" Profile"
                            class="w-32 h-32 rounded-full object-cover">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $user->title }} {{ $user->surname }} {{ $user->firstname }}</h3>
                        <p class="text-gray-600">Member No: {{ $user->member_no }}</p>
                        <p class="text-gray-600">{{ $user->department->name }}, {{ $user->faculty->name }}</p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                        <div class="space-y-3">
                            <p><span class="text-gray-600">Religion:</span> {{ $user->religion ?? 'N/A' }}</p>
                            <p><span class="text-gray-600">Marital Status:</span> {{ $user->marital_status ?? 'N/A' }}</p>
                            <p><span class="text-gray-600">Email:</span> {{ $user->email }}</p>
                            <p><span class="text-gray-600">Phone:</span> {{ $user->phone_number }}</p>
                            <p><span class="text-gray-600">Address:</span> {{ $user->home_address }}</p>
                            <p><span class="text-gray-600">State:</span> {{ $user->state->name }}</p>
                            <p><span class="text-gray-600">LGA:</span> {{ $user->lga->name }}</p>
                        </div>
                    </div>


                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h4>
                        <div class="space-y-3">
                            <p>
                                <span class="text-gray-600">Monthly Savings:</span>
                                <span class="font-medium">₦{{ number_format($user->monthly_savings, 2) }}</span>
                            </p>
                            <p>
                                <span class="text-gray-600">Share Subscription:</span>
                                <span class="font-medium">₦{{ number_format($user->share_subscription, 2) }}</span>
                            </p>
                            <p>
                                <span class="text-gray-600">Month to Commence:</span>
                                <span class="font-medium">{{ $user->month_commence ? date('F Y', strtotime($user->month_commence)) : 'N/A' }}</span>
                            </p>
                        </div>
                    </div>


                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Next of Kin</h4>
                        <div class="space-y-3">
                            <p><span class="text-gray-600">Name:</span> {{ $user->nok }}</p>
                            <p><span class="text-gray-600">Relationship:</span> {{ $user->nok_relationship }}</p>
                            <p><span class="text-gray-600">Phone:</span> {{ $user->nok_phone }}</p>
                            <p><span class="text-gray-600">Address:</span> {{ $user->nok_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h4>
                    <form action="{{ route('profile.password.update') }}" method="POST" class="max-w-md">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px; width: 100%;">
                            </div>
                            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
