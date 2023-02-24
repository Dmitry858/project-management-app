@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('single-task', $task) }}
    <div class="flex flex-wrap">
        <div class="w-full sm:w-2/3 sm:pr-5 mb-6">
            {{ $task->description }}

            @if(count($task->attachments) > 0)
                <h4 class="font-medium leading-tight text-2xl mt-6 mb-2">
                    @lang('titles.attachments')
                </h4>
                @foreach($task->attachments as $attachment)
                    @include('include.attachment')
                @endforeach
            @endif

            <h4 class="font-medium leading-tight text-2xl mt-6 mb-2">
                @lang('titles.comments')
            </h4>
            <div id="comments-wrap">
                @if(count($task->comments) > 0)
                    @foreach($task->comments as $comment)
                        <div class="bg-white p-4 mb-3 border border-gray-300 rounded comment-wrap">
                            <p class="text-sm text-blue-600">
                                {{ $comment->getMemberFullName($comment->member->id) }}
                                <span class="text-xs text-gray-400 ml-1">{{ $comment->created_at->format('d.m.Y H:i:s') }}</span>
                                @if($comment->isEditable())
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pencil inline-block text-gray-400 mx-1 -mt-1 cursor-pointer edit-comment-btn" viewBox="0 0 16 16" data-id="{{ $comment->id }}">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                    </svg>
                                @endif
                                @if($comment->isDeletable())
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash inline-block text-gray-400 -mt-1 cursor-pointer delete-comment-btn" viewBox="0 0 16 16" data-id="{{ $comment->id }}">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                @endif
                                <span class="error text-red-500 ml-1"></span>
                            </p>
                            <p class="comment-text">{{ $comment->comment_text }}</p>
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

                <div class="w-full px-3 mb-6 relative">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_stage')
                    </p>
                    @if(Auth::user()->id == $task->owner->user->id || Auth::user()->id == $task->responsible->user->id)
                        <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="stage_id" name="stage_id" data-task-id="{{ $task->id }}">
                            @if(isset($stages) && count($stages) > 0)
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}" @if($task->stage_id === $stage->id) selected @endif>
                                        {{ $stage->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p id="change-stage-success-message" class="text-xs text-green-600 absolute"></p>
                        <p id="change-stage-error-message" class="text-xs text-red-500 absolute"></p>
                    @else
                        <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                            {{ $task->stage->name }}
                        </p>
                    @endif
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
