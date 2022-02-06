@if ($errors->any())
    <div class="bg-red-500 text-white text-sm font-bold px-4 py-3 mb-6" role="alert">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@elseif (session()->has('error'))
    <div class="bg-red-500 text-white text-sm font-bold px-4 py-3 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif
