@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Profile Update Request Details</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </div>

            <div class="p-6">
                <!-- Member Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Member Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="font-medium">{{ $request->user->title }} {{ $request->user->firstname }} {{ $request->user->surname }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Member Number</p>
                            <p class="font-medium">{{ $request->user->member_no }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Comparison -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Profile Changes</h3>
                    <div class="space-y-4">
                        @foreach($request->getAttributes() as $field => $newValue)
                            @if(!in_array($field, ['id', 'user_id', 'created_at', 'updated_at', 'status', 'admin_remarks']))
                                <div class="border-b pb-2">
                                    <p class="text-sm font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $field)) }}</p>
                                    <div class="flex items-center space-x-4">
                                        @if($field === 'department_id')
                                            <div class="text-blue-600">
                                                <span class="text-sm">Current:</span>
                                                <span class="font-medium">{{ $request->user->department->name ?? 'Not set' }}</span>
                                            </div>
                                            @if($request->user->department_id != $newValue && $newValue)
                                                <span class="text-gray-400">→</span>
                                                <div class="text-red-600">
                                                    <span class="text-sm">Requested:</span>
                                                    <span class="font-medium">{{ \App\Models\Department::find($newValue)->name }}</span>
                                                </div>
                                            @endif
                                        @elseif($field === 'faculty_id')
                                            <div class="text-blue-600">
                                                <span class="text-sm">Current:</span>
                                                <span class="font-medium">{{ $request->user->faculty->name ?? 'Not set' }}</span>
                                            </div>
                                            @if($request->user->faculty_id != $newValue && $newValue)
                                                <span class="text-gray-400">→</span>
                                                <div class="text-red-600">
                                                    <span class="text-sm">Requested:</span>
                                                    <span class="font-medium">{{ \App\Models\Faculty::find($newValue)->name }}</span>
                                                </div>
                                            @endif
                                        @elseif($field === 'state_id')
                                            <div class="text-blue-600">
                                                <span class="text-sm">Current:</span>
                                                <span class="font-medium">{{ $request->user->state->name ?? 'Not set' }}</span>
                                            </div>
                                            @if($request->user->state_id != $newValue && $newValue)
                                                <span class="text-gray-400">→</span>
                                                <div class="text-red-600">
                                                    <span class="text-sm">Requested:</span>
                                                    <span class="font-medium">{{ \App\Models\State::find($newValue)->name }}</span>
                                                </div>
                                            @endif
                                        @elseif($field === 'lga_id')
                                            <div class="text-blue-600">
                                                <span class="text-sm">Current:</span>
                                                <span class="font-medium">{{ $request->user->lga->name ?? 'Not set' }}</span>
                                            </div>
                                            @if($request->user->lga_id != $newValue && $newValue)
                                                <span class="text-gray-400">→</span>
                                                <div class="text-red-600">
                                                    <span class="text-sm">Requested:</span>
                                                    <span class="font-medium">{{ \App\Models\Lga::find($newValue)->name }}</span>
                                                </div>
                                            @endif
                                        @elseif($field === 'member_image')
    <div class="text-blue-600">
        <span class="text-sm">Current:</span>
        <a href="{{ asset('storage/' . $request->user->member_image) }}" target="_blank" class="text-blue-600 hover:underline">
            <img src="{{ asset('storage/' . $request->user->member_image) }}" alt="Current Member Image" class="w-16 h-16 object-cover rounded">
        </a>
    </div>
    @if($request->user->member_image != $newValue && $newValue)
        <span class="text-gray-400">→</span>
        <div class="text-red-600">
            <span class="text-sm">Requested:</span>
            <a href="{{ asset('storage/' . $newValue) }}" target="_blank" class="text-blue-600 hover:underline">
                <img src="{{ asset('storage/' . $newValue) }}" alt="New Member Image" class="w-16 h-16 object-cover rounded">
            </a>
        </div>
    @endif
@elseif($field === 'signature_image')
    <div class="text-blue-600">
        <span class="text-sm">Current:</span>
        <a href="{{ asset('storage/' . $request->user->signature_image) }}" target="_blank" class="text-blue-600 hover:underline">
            <img src="{{ asset('storage/' . $request->user->signature_image) }}" alt="Current Signature" class="w-16 h-16 object-cover rounded">
        </a>
    </div>
    @if($request->user->signature_image != $newValue && $newValue)
        <span class="text-gray-400">→</span>
        <div class="text-red-600">
            <span class="text-sm">Requested:</span>
            <a href="{{ asset('storage/' . $newValue) }}" target="_blank" class="text-blue-600 hover:underline">
                <img src="{{ asset('storage/' . $newValue) }}" alt="New Signature" class="w-16 h-16 object-cover rounded">
            </a>
        </div>
    @endif
@else
    <div class="text-blue-600">
        <span class="text-sm">Current:</span>
        <span class="font-medium">{{ $request->user->$field ?? 'Not set' }}</span>
    </div>
    @if($request->user->$field != $newValue && $newValue)
        <span class="text-gray-400">→</span>
        <div class="text-red-600">
            <span class="text-sm">Requested:</span>
            <span class="font-medium">{{ $newValue }}</span>
        </div>
    @endif                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach                    </div>
                </div>

                @if($request->status === 'pending')
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject
                    </button>
                    <form action="{{ route('admin.profile-updates.approve', $request) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Approve
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Reject Update Request</h3>
            <form action="{{ route('admin.profile-updates.reject', $request) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                    <textarea name="reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
