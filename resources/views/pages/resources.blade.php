@extends('layouts.app')
@section('title', 'Resources | OGITECH Cooperative')

@section('content')
<!-- Hero Section -->
<div class="bg-purple-800 py-16">
   <div class="container mx-auto px-4 text-center">
       <h1 class="text-4xl font-bold text-white mb-4">Member Resources</h1>
       <p class="text-xl text-purple-100">Access important documents and educational materials</p>
   </div>
</div>

<div class="bg-white py-16">
   <div class="container mx-auto px-4">
       <!-- Downloads Section -->
       <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
           <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
               <div class="text-purple-600 mb-6">
                   <i class="fas fa-file-download text-4xl"></i>
               </div>
               <h2 class="text-2xl font-bold text-purple-800 mb-6">Important Forms</h2>
               <ul class="space-y-4">
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-pdf mr-3 text-purple-600"></i>
                           Membership Application Form
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-pdf mr-3 text-purple-600"></i>
                           Loan Application Form
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-pdf mr-3 text-purple-600"></i>
                           Investment Declaration Form
                       </a>
                   </li>
               </ul>
           </div>

           <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
               <div class="text-purple-600 mb-6">
                   <i class="fas fa-book-reader text-4xl"></i>
               </div>
               <h2 class="text-2xl font-bold text-purple-800 mb-6">Financial Education</h2>
               <ul class="space-y-4">
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-graduation-cap mr-3 text-purple-600"></i>
                           Personal Finance Guide
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-chart-line mr-3 text-purple-600"></i>
                           Investment Basics
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-piggy-bank mr-3 text-purple-600"></i>
                           Savings Strategies
                       </a>
                   </li>
               </ul>
           </div>

           <div class="bg-purple-50 p-8 rounded-xl shadow-lg">
               <div class="text-purple-600 mb-6">
                   <i class="fas fa-newspaper text-4xl"></i>
               </div>
               <h2 class="text-2xl font-bold text-purple-800 mb-6">Publications</h2>
               <ul class="space-y-4">
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-alt mr-3 text-purple-600"></i>
                           Annual Report 2023
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-alt mr-3 text-purple-600"></i>
                           Quarterly Newsletter
                       </a>
                   </li>
                   <li>
                       <a href="#" class="flex items-center text-gray-700 hover:text-purple-600 transition-colors">
                           <i class="fas fa-file-alt mr-3 text-purple-600"></i>
                           Member Handbook
                       </a>
                   </li>
               </ul>
           </div>
       </div>

       <!-- FAQs Section -->
       <div class="max-w-4xl mx-auto">
           <div class="flex items-center justify-center gap-4 mb-8">
               <i class="fas fa-question-circle text-4xl text-purple-600"></i>
               <h2 class="text-3xl font-bold text-purple-800">Frequently Asked Questions</h2>
           </div>
           <div class="space-y-4">
               <div class="bg-purple-50 p-6 rounded-xl shadow">
                   <h3 class="text-xl font-bold text-purple-800 mb-2">How do I apply for membership?</h3>
                   <p class="text-gray-700">Download the membership form, fill it out completely, and submit it to our office with the required documents.</p>
               </div>
               <div class="bg-purple-50 p-6 rounded-xl shadow">
                   <h3 class="text-xl font-bold text-purple-800 mb-2">What are the loan requirements?</h3>
                   <p class="text-gray-700">Members must have saved for at least 3 months and maintain regular monthly savings to qualify for loans.</p>
               </div>
               <div class="bg-purple-50 p-6 rounded-xl shadow">
                   <h3 class="text-xl font-bold text-purple-800 mb-2">How are dividends calculated?</h3>
                   <p class="text-gray-700">Dividends are calculated based on your share capital and patronage of the cooperative's services throughout the year.</p>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection
