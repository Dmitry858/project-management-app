@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap">
        <div class="w-full sm:w-2/3 sm:pr-5 mb-6">
            <h4 class="font-medium leading-tight text-3xl mt-0 mb-2">
                @lang('titles.projects_single_subtitle')
            </h4>

            @include('include.tasks-list')
        </div>

        <div class="w-full sm:w-1/3">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_title')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $project->name }}</p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_description')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $project->description }}</p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_activity')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        @if($project->is_active)
                            @lang('form.status_active')
                        @else
                            @lang('form.status_archived')
                        @endif
                    </p>
                </div>

                <div class="w-full px-3">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_members')
                    </p>
                    @if(count($members) > 0)
                        <div class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                            @foreach($members as $member)
                                <p>{{ $member->name }} {{ $member->last_name }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <a class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('projects.edit', ['project' => $project->id]) }}">
                @lang('buttons.edit')
            </a>
        </div>
    </div>
@endsection
