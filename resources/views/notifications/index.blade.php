@extends(auth()->user()->is_admin ? 'layouts.admin' : 'layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Notifications</h2>
                @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white hover:text-purple-200">
                        Mark all as read
                    </button>
                </form>
                @endif
            </div>

            <div class="divide-y divide-gray-200">
                @forelse(auth()->user()->notifications as $notification)
                <div class="p-6 {{ is_null($notification->read_at) ? 'bg-purple-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                @if(isset($notification->data['message']))
                                    {{ $notification->data['message'] }}
                                @endif
                            </h3>
                            <p class="text-gray-600 mt-1">
                                @if(isset($notification->data['status']))
                                    Status: {{ $notification->data['status'] }}
                                @endif
                            </p>
                            <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-purple-600 hover:text-purple-800">
                                Mark as read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    No notifications found
                </div>
                @endforelse
            </div>
            <div class="px-6 py-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
