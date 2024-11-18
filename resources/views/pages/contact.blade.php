@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8">Contact Us</h1>

    <div class="max-w-2xl mx-auto">
        <form action="/contact" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-700 mb-2">Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Message</label>
                <textarea name="message" rows="5" class="w-full p-2 border rounded" required></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Send Message
            </button>
        </form>

        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Contact Information</h2>
            <div class="space-y-4">
                <p><strong>Address:</strong> OGITECH Campus, Igbesa, Ogun State</p>
                <p><strong>Email:</strong> info@ogitechcoop.com</p>
                <p><strong>Phone:</strong> +234 XXX XXX XXXX</p>
            </div>
        </div>
    </div>
</div>
@endsection
