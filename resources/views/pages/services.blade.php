@extends('layouts.app')
@section('title', 'Our Services')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Our Services</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 px-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold mb-4">Savings Plans</h3>
            <ul class="list-disc pl-4">
                <li>Regular Savings</li>
                <li>Target Savings</li>
                <li>Fixed Deposits</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold mb-4">Loan Services</h3>
            <ul class="list-disc pl-4">
                <li>Personal Loans</li>
                <li>Business Loans</li>
                <li>Emergency Loans</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold mb-4">Investment Options</h3>
            <ul class="list-disc pl-4">
                <li>Cooperative Shares</li>
                <li>Investment Plans</li>
                <li>Retirement Schemes</li>
            </ul>
        </div>
    </div>
</div>
@endsection
