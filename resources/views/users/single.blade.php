@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('single-user', $user) }}
    <div class="flex flex-wrap md:w-2/3 lg:w-1/2 -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3 mb-4">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_name')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $user->name }}</p>
        </div>

        <div class="w-full md:w-1/2 px-3 mb-4">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_last_name')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $user->last_name }}</p>
        </div>

        <div class="w-full px-3 mb-4">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_email')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $user->email }}</p>
        </div>

        @if(count($user->roles) > 0)
            <div class="w-full px-3 mb-4">
                <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    @lang('form.label_roles')
                </p>
                <div class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                    @foreach($user->roles as $role)
                        <p>{{ $role->name }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="w-full px-3 mb-4">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_activity')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                @if($user->is_active)
                    @lang('form.status_active')
                @else
                    @lang('form.status_archived')
                @endif
            </p>
        </div>

        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_photo')
            </p>
            <div class="w-1/2 mt-2">
                <img src="{{ $user->photoSrc() }}" alt="{{ $user->name }}">
            </div>
        </div>

        @if($user->member && count($user->member->projects) > 0)
            @permission('view-all-projects')
                <div class="w-full px-3 mb-4">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.projects_member')
                    </p>
                    <div class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        @foreach($user->member->projects as $project)
                            <p class="mb-2"><a href="{{ route('projects.show', ['project' => $project->id]) }}">{{ $project->name }}</a></p>
                        @endforeach
                    </div>
                </div>
            @endpermission
        @endif

        <div class="w-full px-3">
            <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('users.index') }}">
                @lang('buttons.back_to_list')
            </a>
        </div>
    </div>
@endsection
