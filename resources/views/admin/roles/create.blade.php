@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Create New Role</h1>
            <a href="{{ route('admin.roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Back to Roles
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form action="{{ route('admin.roles.store') }}" method="POST" class="p-6">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                        value="{{ old('name') }}" required autofocus style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permissions</label>
                        <div class="ml-4">
                            <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <label for="checkAll" class="ml-2 text-sm text-gray-600">Check All</label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]"
                                value="{{ $permission->id }}"
                                class="permission-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                id="permission_{{ $permission->id }}">
                            <label for="permission_{{ $permission->id }}"
                                class="ml-2 text-sm text-gray-600">
                                {{ $permission->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.getElementsByClassName('permission-checkbox');
        for (let checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>

@endsection
