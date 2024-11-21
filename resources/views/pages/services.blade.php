@extends('layouts.app')
@section('title', 'Our Services | OGITECH Cooperative')
@section('content')

<!-- Hero Section -->
<div class="bg-purple-800 py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Our Services & Operations</h1>
        <p class="text-xl text-purple-100">Comprehensive financial solutions for our valued members</p>
    </div>
</div>

<!-- Operations Section -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto mb-16">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-cogs text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Our Operations</h2>
            </div>
            <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
                <p class="text-gray-700 leading-relaxed">
                    Our Society grants regular loans, emergency loans, publication loans, electronic and equipment loans, and returns on fixed deposits and other investments as key operations. In addition, the Society engages in regular (festive periods are cogent) commodity sales of bags of rice, gallons of vegetable oil, and other Fast Moving Consumer Goods (FMCGs), amongst others, to members as a form of welfare package. Also, giving to the less privileged is of great importance to us.
                </p>
            </div>
        </div>

        <!-- Products & Services Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Regular Loan -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-money-bill-wave text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Regular Loan</h3>
                <p class="text-gray-700 mb-4">Members are eligible for a regular loan facility of not more than 200% of their ordinary savings balance with a maximum repayment period of 18 months.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Up to 200% of savings</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>18 months repayment</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Competitive rates</li>
                </ul>
            </div>

            <!-- Emergency Loan -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-ambulance text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Emergency Loan</h3>
                <p class="text-gray-700 mb-4">Members are entitled to an emergency loan facility of not more than 100% of their ordinary savings balance with a maximum repayment period of 3 months.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Quick approval</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>3 months repayment</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Flexible terms</li>
                </ul>
            </div>

            <!-- Publication Loan -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-book text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Publication Loan</h3>
                <p class="text-gray-700 mb-4">Members are entitled to a loan facility for the publication of an academic textbook with a maximum repayment period of 3 months.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Academic support</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>3 months repayment</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Career development</li>
                </ul>
            </div>

            <!-- Electronic and Equipment Loan -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-laptop text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Electronic & Equipment Loan</h3>
                <p class="text-gray-700 mb-4">Members are eligible for a loan facility of not more than an ordinary savings balance or NGN500,000 maximum with a repayment period of 10 months.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Up to ₦500,000</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>10 months repayment</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Flexible terms</li>
                </ul>
            </div>

            <!-- Commodity Sales -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-shopping-basket text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Commodity Sales</h3>
                <p class="text-gray-700 mb-4">Members are entitled to regular and festival commodity sales with a maximum repayment period of 3 months.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Essential goods</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Festival packages</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Flexible payment</li>
                </ul>
            </div>

            <!-- Advisory Services -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-comments text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-purple-800 mb-4">Advisory Services</h3>
                <p class="text-gray-700 mb-4">Members are entitled to the best advice on profitable business ideas to invest their loan facilities.</p>
                <ul class="text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Expert guidance</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Business planning</li>
                    <li><i class="fas fa-check text-purple-600 mr-2"></i>Investment advice</li>
                </ul>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-center gap-4 mb-8">
                <i class="fas fa-gift text-4xl text-purple-600"></i>
                <h2 class="text-3xl font-bold text-purple-800">Member Benefits</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-purple-50 p-6 rounded-xl shadow">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                            <span>Investment Opportunities</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-money-bill-wave text-purple-600 mr-3"></i>
                            <span>Access to Regular and Emergency Loans</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-home text-purple-600 mr-3"></i>
                            <span>Landed Property Acquisition</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-purple-50 p-6 rounded-xl shadow">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <i class="fas fa-percentage text-purple-600 mr-3"></i>
                            <span>Dividend Gains</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-tv text-purple-600 mr-3"></i>
                            <span>Household Equipment Loan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-shopping-cart text-purple-600 mr-3"></i>
                            <span>Regular Commodity Sales</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
