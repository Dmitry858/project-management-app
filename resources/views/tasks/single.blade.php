@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap">
        <div class="w-full sm:w-2/3 sm:pr-5 mb-6">
            {{ $task->description }}
            <h4 class="font-medium leading-tight text-3xl mt-6 mb-2">
                @lang('titles.comments')
            </h4>
            Здесь комментарии
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
                        Стадия
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->stage->name }}
                    </p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Крайний срок
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
                            Активный
                        @else
                            В архиве
                        @endif
                    </p>
                </div>

                <div class="w-full px-3 mb-6">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_owner')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->owner->user->name }}
                    </p>
                </div>

                <div class="w-full px-3">
                    <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        @lang('form.label_responsible')
                    </p>
                    <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                        {{ $task->responsible->user->name }}
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
