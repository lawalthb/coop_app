@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <!-- <img src="{{ asset('images/logo.png') }}" alt="OGITECH COOP" class="h-20 mx-auto mb-4"> -->
                <h2 class="text-3xl font-bold text-purple-800">Reset Password</h2>
                <p class="text-gray-600 mt-2">Enter your new password below</p>
            </div>

            <!-- Reset Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required autofocus style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">New Password</label>
                        <input type="password" name="password"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
