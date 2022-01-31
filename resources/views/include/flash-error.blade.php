@if (session()->has('error'))
    <div class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif
