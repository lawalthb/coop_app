@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Profile Update Requests</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($requests as $request)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $request->user->title }} {{ $request->user->firstname }} {{ $request->user->surname }}
                            </h3>
                            <p class="text-sm text-gray-500">Submitted: {{ $request->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <a href="{{ route('admin.profile-updates.show', $request) }}"
                           class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Review Request
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    No profile update requests found
                </div>
                @endforelse
            </div>

            <div class="px-6 py-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
