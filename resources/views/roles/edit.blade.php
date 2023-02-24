@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('edit-role', $role) }}
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    @lang('form.label_title')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" value="{{ $role->name }}" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="slug">
                    @lang('form.label_slug')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="slug" name="slug" type="text" value="{{ $role->slug }}" required>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="permissions">
                    @lang('form.label_permissions')
                </label>
                @if(isset($permissions) && count($permissions) > 0)
                    @foreach($permissions as $permission)
                        @if(!in_array($permission->id, config('app.permissions_only_for_admin')))
                            <div class="form-check">
                                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" @if($permission->roles->where('id', $role->id)->count() > 0) checked @endif>
                                <label class="form-check-label inline-block text-gray-800" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('roles.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
