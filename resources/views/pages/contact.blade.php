@extends('layouts.app')
@section('title', 'Contact Us | OGITECH Cooperative')

@section('content')
<!-- Hero Section -->
<div class="bg-purple-800 py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Get In Touch</h1>
        <p class="text-xl text-purple-100">We're here to help and answer any questions you might have</p>
    </div>
</div>

<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <!-- Contact Information -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-purple-800 mb-6">Contact Information</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Visit Us</h3>
                                <p class="text-gray-600">OGITECH Campus, Igbesa<br>Ogun State, Nigeria</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-phone text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Call Us</h3>
                                <p class="text-gray-600">+234 XXX XXX XXXX</p>
                                <p class="text-gray-600">+234 XXX XXX XXXX</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-envelope text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Email Us</h3>
                                <p class="text-gray-600">info@ogitechcoop.com</p>
                                <p class="text-gray-600">support@ogitechcoop.com</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-clock text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Meeting Hours</h3>

                                <p class="text-gray-600">Thursday: 12:00 PM - 1:00 PM</p>
                                <p class="text-gray-600">Saturday (Online): 7:00PM - 8:00PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-purple-100 p-3 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fab fa-facebook text-purple-600"></i>
                            </a>
                            <a href="#" class="bg-purple-100 p-3 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fab fa-twitter text-purple-600"></i>
                            </a>
                            <a href="#" class="bg-purple-100 p-3 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fab fa-instagram text-purple-600"></i>
                            </a>
                            <a href="#" class="bg-purple-100 p-3 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fab fa-linkedin text-purple-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-purple-800 mb-6">Send Us a Message</h2>
                <form action="/contact" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Subject</label>
                        <input type="text" name="subject" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Message</label>
                        <textarea name="message" rows="5" class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-paper-plane mr-2"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-16 max-w-6xl mx-auto">
            <div class="bg-purple-50 p-4 rounded-xl">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.4751789083938!2d2.7172777!3d6.6783333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8e3c3f3f3f3f%3A0x3f3f3f3f3f3f3f3f!2sOgun%20State%20Institute%20of%20Technology%2C%20Igbesa!5e0!3m2!1sen!2sng!4v1629788000000!5m2!1sen!2sng"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    class="rounded-lg">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection