@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        <input class="hidden" type="checkbox" name="clear_cache" value="1" checked>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.clear_cache_button')
        </button>
    </form>
@endsection
