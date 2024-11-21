<div class="fixed top-4 right-4 z-50">
    @if(session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('warning') }}
        </div>
    </div>
    @endif
</div>
