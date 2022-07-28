@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($tasks) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['text' => 'Название'])
                                    @include('include.table-th', ['text' => 'Проект'])
                                    @include('include.table-th', ['text' => 'Постановщик'])
                                    @include('include.table-th', ['text' => 'Ответственный'])
                                    @include('include.table-th', ['text' => 'Стадия'])
                                    @include('include.table-th', ['text' => __('form.label_activity')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="bg-white border-b">
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('tasks.show', ['task' => $task->id]) }}">{{ $task->name }}</a>
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->project->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->owner->user->name }} {{ $task->owner->user->last_name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->responsible->user->name }} {{ $task->responsible->user->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $task->stage->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->is_active ? 'Активный' : 'В архиве' }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @permission('edit-tasks')
                                                @include('include.buttons.edit', ['link' => route('tasks.edit', ['task' => $task->id])])
                                            @endpermission
                                            @permission('delete-tasks')
                                                @include('include.buttons.delete', ['link' => route('tasks.destroy', ['task' => $task->id])])
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $tasks->links() }}
    @else
        <p>@lang('empty.tasks')</p>
    @endif

    @permission('create-tasks')
        <div class="py-4 mt-4">
            @include('include.buttons.create', ['link' => route('tasks.create')])
        </div>
    @endpermission
@endsection
