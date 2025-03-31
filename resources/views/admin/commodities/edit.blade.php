@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Commodity</h2>
            </div>

            <form action="{{ route('admin.commodities.update', $commodity) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name*</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $commodity->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                  style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">{{ old('description', $commodity->description) }}</textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (₦)*</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $commodity->price) }}" required step="0.01" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div>
                        <label for="quantity_available" class="block text-sm font-medium text-gray-700">Slot Available*</label>
                        <input type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', $commodity->quantity_available) }}" required min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $commodity->start_date ? $commodity->start_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $commodity->end_date ? $commodity->end_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        @if($commodity->image)
                        <div class="mt-2 mb-4">
                            <img src="{{ asset('storage/' . $commodity->image) }}" alt="{{ $commodity->name }}" class="h-32 w-32 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Current image. Upload a new one to replace it.</p>
                        </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                                     file:mr-4 file:py-2 file:px-4
                                     file:rounded-md file:border-0
                                     file:text-sm file:font-semibold
                                     file:bg-purple-50 file:text-purple-700
                                     hover:file:bg-purple-100">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="purchase_amount" class="block text-sm font-medium text-gray-700">Purchase Amount (₦)</label>
                            <input type="number" name="purchase_amount" id="purchase_amount" value="{{ old('purchase_amount', $commodity->purchase_amount) }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="target_sales_amount" class="block text-sm font-medium text-gray-700">Target Sales Amount (₦)</label>
                            <input type="number" name="target_sales_amount" id="target_sales_amount" value="{{ old('target_sales_amount', $commodity->target_sales_amount) }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                        <div>
                            <label for="profit_amount" class="block text-sm font-medium text-gray-700">Profit Amount (₦)</label>
                            <input type="number" name="profit_amount" id="profit_amount" value="{{ old('profit_amount', $commodity->profit_amount) }}" step="0.01"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                        </div>
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $commodity->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.commodities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </a>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Commodity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
