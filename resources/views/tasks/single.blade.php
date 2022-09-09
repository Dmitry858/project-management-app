@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap">
        <div class="w-full sm:w-2/3 sm:pr-5 mb-6">
            {{ $task->description }}
            <h4 class="font-medium leading-tight text-3xl mt-6 mb-2">
                @lang('titles.comments')
            </h4>
            <div id="comments-wrap">
                @if(count($task->comments) > 0)
                    @foreach($task->comments as $comment)
                        <div class="bg-white p-4 mb-3 border border-gray-300 rounded">
                            <p class="text-sm text-blue-600">
                                {{ $comment->member->user->name }} {{ $comment->member->user->last_name }}
                                <span class="text-xs text-gray-400 ml-1">{{ $comment->created_at->format('d.m.Y H:i:s') }}</span>
                            </p>
                            <p>{{ $comment->comment_text }}</p>
                        </div>
                    @endforeach
                @endif
            </div>

            @if(Auth::user()->id == $task->owner->user->id || Auth::user()->id == $task->responsible->user->id)
                @include('include.add-comment')
            @else
                @permission('add-comments')
                    @include('include.add-comment')
                @endpermission
            @endif
        </div>

        <div class="w-full sm:w-1/3">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_project')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">{{ $task->project->name }}</p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_stage')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->stage->name }}
                    </p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_deadline')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        @if($task->deadline)
                            {{ $task->deadline }}
                        @else
                            --
                        @endif
                    </p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_activity')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        @if($task->is_active)
                            @lang('form.status_active')
                        @else
                            @lang('form.status_archived')
                        @endif
                    </p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_owner')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->getMemberFullName($task->owner_id) }}
                    </p>
                </div>

                <div class="w-full px-3">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_responsible')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->getMemberFullName($task->responsible_id) }}
                    </p>
                </div>
            </div>

            @permission('edit-tasks')
                <a class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('tasks.edit', ['task' => $task->id]) }}">
                    @lang('buttons.edit')
                </a>
            @endpermission
        </div>
    </div>
@endsection
