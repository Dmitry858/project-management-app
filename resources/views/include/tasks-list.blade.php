@if (isset($tasks) && count($tasks) > 0)
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="py-2 inline-block min-w-full">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-blue-300 border-b">
                            <tr>
                                @include('include.table-th', ['text' => __('table.col_title')])
                                @if(isset($mode) && $mode === 'full')
                                    @include('include.table-th', ['text' => __('table.col_project')])
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
                                <tr class="bg-white border-b">
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('tasks.show', ['task' => $task->id]) }}">{{ $task->name }}</a>
                                    </td>
                                    @if(isset($mode) && $mode === 'full')
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $task->project->name }}
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
