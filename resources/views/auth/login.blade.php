@extends('layouts.app')
@section('title', 'Login | OGITECH Cooperative')

@section('content')
<div class="min-h-screen bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-purple-800">Welcome Back!</h2>
                <p class="text-gray-600 mt-2">Sign in to access your account</p>
            </div>

            <!-- Login Card -->
            <!-- success message will be here -->
            <!-- Success Notification -->
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                            <input type="email" name="email"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('email') border-red-500 @enderror"
                                required autofocus style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password" name="password"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('password') border-red-500 @enderror"
                                required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                class="rounded border-gray-300 text-purple-600 focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <label for="remember" class="ml-2 text-gray-700">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </button>


                </form>
            </div>

            <!-- Security Notice -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p class="flex items-center justify-center">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>
                    Your login is protected by industry-standard encryption
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
