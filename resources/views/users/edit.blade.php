@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('edit-user', $user) }}
    @include('include.flash-success')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('users.update', ['user' => $user->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    @lang('form.label_name')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" value="{{ $user->name }}" required>
            </div>

            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="last_name">
                    @lang('form.label_last_name')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="last_name" name="last_name" type="text" value="{{ $user->last_name }}">
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
                    @lang('form.label_email')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" name="email" type="text" value="{{ $user->email }}" required>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="roles">
                    @lang('form.label_roles')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="roles" name="roles[]" multiple>
                    @if(isset($roles) && count($roles) > 0)
                        @foreach($roles as $role)
                            <option
                                value="{{ $role->id }}"
                                @if($user->roles->contains($role->id)) selected @endif
                            >
                                {{ $role->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_active">
                    @lang('form.label_activity')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_active" name="is_active">
                    <option value="1" @if($user->is_active) selected @endif>Активный</option>
                    <option value="0" @if(!$user->is_active) selected @endif>В архиве</option>
                </select>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="photo">
                    @lang('form.label_photo')
                </label>
                @if($user->photo)
                    <div class="w-1/2 mt-2">
                        <img src="{{ $user->photoSrc() }}" alt="{{ $user->name }}">
                    </div>
                    <details class="text-gray-700">
                        <summary>@lang('form.change_link')</summary>
                        <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="photo" name="photo" type="file" accept="image/*">
                    </details>
                @else
                    <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="photo" name="photo" type="file" accept="image/*">
                @endif
            </div>
        </div>

        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="new_password">
                    @lang('form.label_new_password')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="new_password" name="password" type="password">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="confirm_new_password">
                    @lang('form.label_confirm_new_password')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="confirm_new_password" name="password_confirmation" type="password">
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('users.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
