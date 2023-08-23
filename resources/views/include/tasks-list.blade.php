@if (isset($tasks) && count($tasks) > 0)
    @if(isset($page) && $page === 'search')
        <h2 class="font-medium text-lg mb-2">@lang('titles.tasks_search_subtitle', ['phrase' => $phrase])</h2>
    @endif

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="py-2 inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-blue-300 border-b">
                            <tr>
                                @permission('delete-tasks')
                                    @include('include.table-th', ['type' => 'checkbox'])
                                @endpermission
                                @include('include.table-th', ['text' => __('table.col_title')])
                                @if(isset($mode) && $mode === 'full')
                                    @include('include.table-th', ['text' => __('table.col_deadline')])
                                    @include('include.table-th', ['text' => __('table.col_owner')])
                                @endif
                                @include('include.table-th', ['text' => __('table.col_responsible')])
                                @include('include.table-th', ['text' => __('table.col_stage')])
                                @include('include.table-th', ['text' => __('form.label_activity')])
                                @include('include.table-th', ['text' => ''])
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $task->id }}" data-entity="tasks">
                                    @permission('delete-tasks')
                                        @include('include.table-td', ['type' => 'checkbox'])
                                    @endpermission
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('tasks.show', ['task' => $task->id]) }}" title="{{ $task->name }}">
                                            {{ Str::limit($task->name, 40) }}
                                        </a>
                                        <br>
                                        <span class="text-xs text-gray-600">
                                            @lang('table.col_project') {{ Str::limit($task->project->name, 25) }}
                                        </span>
                                    </td>
                                    @if(isset($mode) && $mode === 'full')
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @if($task->deadline)
                                                {{ $task->deadline }}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->getMemberFullName($task->owner_id) }}
                                        </td>
                                    @endif
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ $task->getMemberFullName($task->responsible_id) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $task->stage->name }}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        @if($task->is_active)
                                            @lang('form.status_active')
                                        @else
                                            @lang('form.status_archived')
                                        @endif
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <div class="flex">
                                            @permission('edit-tasks')
                                            @include('include.buttons.edit', ['link' => route('tasks.edit', ['task' => $task->id])])
                                            @endpermission
                                            @permission('delete-tasks')
                                            @include('include.buttons.delete', ['link' => route('tasks.destroy', ['task' => $task->id])])
                                            @endpermission
                                        </div>
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
    @if(isset($page) && $page === 'search')
        <p>@lang('empty.tasks_search', ['phrase' => $phrase])</p>
    @else
        <p>@lang('empty.tasks')</p>
    @endif
@endif

<div class="flex items-center py-4 mt-2">
    @permission('create-tasks')
        @include('include.buttons.create', [
            'link' => isset($project) ? route('tasks.create') . '?project_id=' . $project->id : route('tasks.create')
        ])
    @endpermission
    @permission('delete-tasks')
        <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
            @lang('buttons.delete_selected')
        </a>
    @endpermission
</div>
