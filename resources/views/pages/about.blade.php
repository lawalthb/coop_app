@extends('layouts.app')
@section('title', 'About Us | OGITECH Cooperative')
@section('content')

<!-- Hero Section with Image -->
<div class="relative h-96">
    <img src="{{ asset('images/about.jpg') }}" alt="About OGITECH COOP" class="w-3/4 h-full object-contain mx-auto">
    <div class="absolute inset-0 bg-purple-900 bg-opacity-60">
        <div class="container mx-auto px-4 h-full flex items-center">
            <h1 class="text-4xl md:text-5xl text-white font-bold">About Our Cooperative</h1>
        </div>
    </div>
</div>
<!-- Who We Are Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-users text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Who We Are</h2>
            </div>
            <p class="text-lg text-gray-700 leading-relaxed text-center">
                We are a growing Cooperative Society situated at the Ogun State Institute of Technology, Igbesa, Ogun State in Nigeria determined with great focus to better members' lives and our immediate environment.
            </p>
        </div>
    </div>
</div>

<!-- Mission & Vision Section -->
<div class="bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <i class="fas fa-bullseye text-3xl text-purple-600"></i>
                    <h3 class="text-2xl font-bold text-purple-800">Our Mission</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    To be a world-class Cooperative Society situated in Nigeria, continuously making great impact offering financial solutions and otherwise that promote members' well-being, sustainability, and economic empowerment, thereby contributing to improving their lives and that of their immediate environment hence paving the way for a better future.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <i class="fas fa-eye text-3xl text-purple-600"></i>
                    <h3 class="text-2xl font-bold text-purple-800">Our Vision</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    To reduce poverty, promote economic empowerment and freedom, create wealth and a better future so as to improve the quality of life for members, through our strategic operations, timely provision of accessible financial solutions, and non-financial services like mortgage facilities, entrepreneurial development, advisory services, and educational upliftment leading to a brighter future for members and their immediate environment.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- History Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-history text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Our History</h2>
            </div>
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
                <div class="prose max-w-none text-gray-700">
                    <p class="mb-4">The journey to establishing OGITECH ACADEMIC STAFF COOPERATIVE MULTIPURPOSE SOCIETY started with a motion raised by Comr. Eletu, Ahmed Ajiboye on the floor of the ASUP, OGITECH Chapter congress held in November 2021.</p>

                    <p class="mb-4">On Thursday, 17th March 2022, the ASUP Cooperative Committee was set up by Comr. Gafar, Oluwasegun Quadri led executive immediately after they were sworn into office at the congress of ASUP, OGITECH Chapter.</p>

                    <p class="mb-4">The committee began by first considering the prevailing situation surrounding the existing cooperative societies in the Institute highlighting both good and unethical practices in their custom over the years with a view of establishing a Society with a difference and welfare of members at heart.</p>

                    <p>5th April 2022 signifies the starting date of the society named OGITECH ACADEMIC STAFF COOPERATIVE MULTIPURPOSE SOCIETY.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="bg-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-heart text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Our Values</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <i class="fas fa-handshake text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-purple-800 mb-2">Integrity</h3>
                    <p class="text-gray-600">We maintain the highest standards of honesty and transparency</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <i class="fas fa-users text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-purple-800 mb-2">Cooperation</h3>
                    <p class="text-gray-600">Working together for mutual benefit and growth</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                    <i class="fas fa-star text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-purple-800 mb-2">Excellence</h3>
                    <p class="text-gray-600">Striving for the highest quality in all our services</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
