@extends('layouts.app')
@section('title', 'Events | OGITECH Cooperative')

@section('content')
<!-- Banner Section -->
<div class="relative h-96">
    <img src="{{ asset('images/event-banner.jpg') }}" alt="Events Banner" class="w-3/4 h-full object-contain mx-auto">
    <div class="absolute inset-0 bg-purple-900 bg-opacity-60">
        <div class="container mx-auto px-4 h-full flex items-center">
            <div class="text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Cooperative Events</h1>
                <p class="text-xl">Join us in building a stronger community through shared experiences</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <!-- Upcoming Events -->
        <div class="mb-16">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-calendar-alt text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Upcoming Events</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- AGM -->
                <div class="bg-purple-50 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">JAN 15</span>
                        <span class="block text-sm">2024</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-purple-800 mb-2">Annual General Meeting</h3>
                        <p class="text-gray-600 mb-4">Join us for our annual general meeting where we'll discuss our achievements and future plans.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            <span>10:00 AM - 2:00 PM</span>
                        </div>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Main Auditorium, OGITECH</span>
                        </div>
                        <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Register Now</a>
                    </div>
                </div>

                <!-- Financial Workshop -->
                <div class="bg-purple-50 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">FEB 1</span>
                        <span class="block text-sm">2024</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-purple-800 mb-2">Financial Workshop</h3>
                        <p class="text-gray-600 mb-4">Learn about personal finance management and investment strategies.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            <span>2:00 PM - 4:00 PM</span>
                        </div>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Conference Room, Admin Block</span>
                        </div>
                        <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Register Now</a>
                    </div>
                </div>

                <!-- Investment Seminar -->
                <div class="bg-purple-50 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">FEB 15</span>
                        <span class="block text-sm">2024</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-purple-800 mb-2">Investment Seminar</h3>
                        <p class="text-gray-600 mb-4">Expert insights on maximizing your investment returns.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            <span>1:00 PM - 4:00 PM</span>
                        </div>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Lecture Theatre 1</span>
                        </div>
                        <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Register Now</a>
                    </div>
                </div>

                <!-- Members Forum -->
                <div class="bg-purple-50 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-purple-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">MAR 5</span>
                        <span class="block text-sm">2024</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-purple-800 mb-2">Members Forum</h3>
                        <p class="text-gray-600 mb-4">Open discussion on cooperative improvements and member suggestions.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            <span>3:00 PM - 5:00 PM</span>
                        </div>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Conference Hall</span>
                        </div>
                        <a href="#" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Register Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Events -->
        <div>
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-history text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Past Events</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- End of Year Party -->
                <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden opacity-75">
                    <div class="bg-gray-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">DEC 10</span>
                        <span class="block text-sm">2023</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">End of Year Party</h3>
                        <p class="text-gray-600 mb-4">Members gathered to celebrate our achievements in 2023.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-users mr-2"></i>
                            <span>150+ Attendees</span>
                        </div>
                        <a href="#" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">View Gallery</a>
                    </div>
                </div>

                <!-- Investment Workshop -->
                <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden opacity-75">
                    <div class="bg-gray-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">NOV 15</span>
                        <span class="block text-sm">2023</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Investment Workshop</h3>
                        <p class="text-gray-600 mb-4">Expert-led session on wealth creation and management.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-users mr-2"></i>
                            <span>80+ Participants</span>
                        </div>
                        <a href="#" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">View Summary</a>
                    </div>
                </div>

                <!-- Financial Literacy Seminar -->
                <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden opacity-75">
                    <div class="bg-gray-600 text-white p-4 text-center">
                        <span class="text-2xl font-bold">OCT 20</span>
                        <span class="block text-sm">2023</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Financial Literacy Seminar</h3>
                        <p class="text-gray-600 mb-4">Educational session on personal finance management.</p>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-users mr-2"></i>
                            <span>100+ Participants</span>
                        </div>
                        <a href="#" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">View Resources</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
