@extends('layouts.app')
@section('title', 'Resources')


@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8">Resources</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Downloads</h2>
            <ul class="space-y-4">
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Membership Form</a>
                </li>
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Loan Application Form</a>
                </li>
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Annual Report 2023</a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Financial Education</h2>
            <ul class="space-y-4">
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Budgeting Guide</a>
                </li>
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Investment Basics</a>
                </li>
                <li>
                   <a href="#" class="text-purple-600 hover:underline">Savings Tips</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
