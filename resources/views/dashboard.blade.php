@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap">
        <div class="w-full sm:w-1/2 sm:pr-5 mb-6">
            <h2 class="font-medium leading-tight text-xl mb-3">@lang('titles.new_tasks')</h2>
            @if(count($tasks) > 0)
                @foreach($tasks as $task)
                    @include('include.dashboard-list-item', ['entity' => 'tasks', 'entityObj' => $task])
                @endforeach
            @else
                <div>
                    @lang('empty.new_tasks')
                </div>
            @endif
            <a class="inline-block bg-blue-600 hover:bg-blue-700 transition text-white font-bold mt-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('tasks.index') }}">
                @lang('buttons.all_tasks')
            </a>
        </div>

        <div class="w-full sm:w-1/2">
            <h2 class="font-medium leading-tight text-xl mb-3">@lang('titles.active_projects')</h2>
            @if(count($projects) > 0)
                @foreach($projects as $project)
                    @include('include.dashboard-list-item', ['entity' => 'projects', 'entityObj' => $project])
                @endforeach
            @else
                <div>
                    @lang('empty.active_projects')
                </div>
            @endif
            <a class="inline-block bg-blue-600 hover:bg-blue-700 transition text-white font-bold mt-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('projects.index') }}">
                @lang('buttons.all_projects')
            </a>
        </div>
    </div>
@endsection
