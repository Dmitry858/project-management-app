@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_title')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $role->name }}</p>
        </div>

        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_slug')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $role->slug }}</p>
        </div>

        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_permissions')
            </p>
            @if(count($role->permissions) > 0)
                @foreach($role->permissions as $permission)
                    <p class="text-gray-700 py-2 leading-tight">
                        {{ $permission->name }}
                    </p>
                @endforeach
            @endif
        </div>

        @permission('view-users')
            @if(count($role->users) > 0)
                <div class="w-full px-3 mb-6">
                    <h4 class="font-medium leading-tight text-2xl mt-3 mb-2">
                        @lang('titles.users_with_role')
                    </h4>
                    @foreach($role->users as $user)
                        <p class="text-gray-700 py-2 leading-tight">
                            {{ $user->name }} {{ $user->last_name }}
                        </p>
                    @endforeach
                </div>
            @endif
        @endpermission

        <div class="w-full px-3">
            <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('roles.index') }}">
                @lang('buttons.back_to_list')
            </a>
        </div>
    </div>
@endsection
