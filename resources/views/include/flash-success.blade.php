@if (session()->has('success'))
    <div class="flex items-center bg-green-500 text-white text-sm font-bold px-4 py-3 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
