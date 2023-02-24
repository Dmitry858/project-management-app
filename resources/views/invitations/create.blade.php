@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('create-invitation') }}
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('invitations.store') }}">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="secret_key">
                    @lang('form.label_secret_key')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="secret_key" name="secret_key" type="text" value="{{ Str::random(32) }}" required readonly>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
                    @lang('form.label_email')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" name="email" type="text" value="" required>
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('invitations.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
