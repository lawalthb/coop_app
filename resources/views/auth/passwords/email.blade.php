@extends('layouts.app')
@section('title', 'Reset Password | OGITECH Cooperative')

@section('content')
<div class="min-h-screen bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <!-- <img src="{{ asset('images/logo.png') }}" alt="OGITECH COOP" class="h-20 mx-auto mb-4"> -->
                <h2 class="text-3xl font-bold text-purple-800">Reset Password</h2>
                <p class="text-gray-600 mt-2">Enter your email to receive reset instructions</p>
            </div>

            <!-- Reset Card -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-medium">Success!</p>
                    <p>{{ session('status') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                            <input type="email" name="email"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}"
                                required autofocus style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-paper-plane mr-2"></i> Send Reset Link
                    </button>

                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p class="flex items-center justify-center">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>
                    Password reset links expire after 60 minutes
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
