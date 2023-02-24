@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('create-project') }}
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    @lang('form.label_title')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" value="" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                    @lang('form.label_description')
                </label>
                <textarea class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" name="description" type="text" value=""></textarea>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_active">
                    @lang('form.label_activity')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_active" name="is_active">
                    <option value="1">@lang('form.status_active')</option>
                    <option value="0">@lang('form.status_archived')</option>
                </select>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="members">
                    @lang('form.label_members')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="members" name="members[]" multiple>
                    @if(isset($users) && count($users) > 0)
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
    </form>
@endsection
