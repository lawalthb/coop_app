@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Share Details</h2>
                <a href="{{ route('admin.shares.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Certificate Information -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800 mb-2">Certificate Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Certificate Number:</span>
                            <span class="font-medium">{{ $share->certificate_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($share->status) }}
                            </span>
                        </div>
                    </div>
