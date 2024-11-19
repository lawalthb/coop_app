@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center text-purple-800 mb-6">Login to OGITECH COOP</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full rounded border-gray-300 @error('email') border-red-500 @enderror" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full rounded border-gray-300 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-purple-600">
                        <label for="remember" class="ml-2 text-gray-700">Remember me</label>
                    </div>
                    <a href="" class="text-purple-600 hover:text-purple-800">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                    Login
                </button>

                <div class="text-center mt-4">
                    <span class="text-gray-600">Don't have an account?</span>
                    <a href="" class="text-purple-600 hover:text-purple-800 ml-1">Register here</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
