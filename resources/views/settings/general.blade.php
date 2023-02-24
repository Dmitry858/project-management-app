@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('general-settings') }}
    @include('include.flash-success')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('settings.general.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="pointer-events-none block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="logo">
                    @lang('form.label_logo')
                </label>
                @if(Storage::disk('public')->exists('logo.png'))
                    <div class="w-1/2 mt-2 relative">
                        <input class="hidden" type="checkbox" name="logo_exists" value="1" checked>
                        <img src="{{ asset('storage/logo.png') }}" alt="{{ __('header.logo') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" id="delete-logo-btn" class="bi bi-x-square cursor-pointer absolute top-0 -right-6" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <details class="text-gray-700">
                        <summary>@lang('form.change_link')</summary>
                        <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="logo" name="logo" type="file" accept="image/png">
                        <span class="text-xs text-gray-700">@lang('form.logo_input_hint')</span>
                    </details>
                @else
                    <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="logo" name="logo" type="file" accept="image/png">
                    <span class="text-xs text-gray-700">@lang('form.logo_input_hint')</span>
                @endif
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
    </form>
@endsection
