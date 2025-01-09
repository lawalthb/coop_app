@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">

                <h2 class="text-2xl font-semibold mb-6">New Share Purchase </h2>

                <form action="{{ route('admin.shares.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                            <select name="user_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Member</option>
                                @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->surname }} {{ $member->firstname }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Share Type</label>
                            <select name="share_type_id" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Share Type</option>
                                @foreach($shareTypes as $type)
                                <option value="{{ $type->id }}" {{ old('share_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} (₦{{ number_format($type->price_per_unit, 2) }} per unit)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Units</label>
                            <input type="number" name="number_of_units" value="{{ old('number_of_units') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Remark</label>
                            <textarea name="remark" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">{{ old('remark') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                                Submit Purchase
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
