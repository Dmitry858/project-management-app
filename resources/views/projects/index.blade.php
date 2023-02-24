@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('projects') }}
    @include('include.flash-success')
    @include('include.flash-error')
    @permission('view-all-projects')
        @include('include.filter', ['entity' => 'projects'])
    @endpermission

    @if (count($projects) > 0)
        <div class="flex flex-col overflow-x-auto">
            <div class="py-2 min-w-full">
                <table class="min-w-full">
                    <thead class="bg-blue-300 border-b">
                        <tr>
                            @include('include.table-th', ['text' => __('table.col_title')])
                            @include('include.table-th', ['text' => __('table.col_description')])
                            @include('include.table-th', ['text' => __('table.col_members')])
                            @include('include.table-th', ['text' => __('form.label_activity')])
                            @include('include.table-th', ['text' => ''])
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr class="bg-white border-b hover:bg-blue-100 transition">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('projects.show', ['project' => $project->id]) }}" title="{{ $project->name }}">{{ Str::limit($project->name, 40) }}</a>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('projects.show', ['project' => $project->id]) }}" title="{{ $project->description }}">{{ Str::limit($project->description, 60) }}</a>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <div class="has-tooltip inline-block relative cursor-default">
                                        <span>{{ $project->members->count() }}</span>
                                        @if($project->members->count() > 0)
                                            <div @class([
                                                'tooltip',
                                                'rounded',
                                                'shadow-lg',
                                                'p-2',
                                                'bg-gray-100',
                                                'tooltip-bottom' => $loop->last
                                            ])>
                                                {!! membersListHtml($project->members) !!}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    @if($project->is_active)
                                        @lang('form.status_active')
                                    @else
                                        @lang('form.status_archived')
                                    @endif
                                </td>
                                <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    @permission('edit-projects')
                                        @include('include.buttons.edit', ['link' => route('projects.edit', ['project' => $project->id])])
                                    @endpermission
                                    @permission('delete-projects')
                                        @include('include.buttons.delete', ['link' => route('projects.destroy', ['project' => $project->id])])
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $projects->links() }}
    @else
        <p>@lang('empty.projects')</p>
    @endif

    @permission('create-projects')
        <div class="py-4 mt-4">
            @include('include.buttons.create', ['link' => route('projects.create')])
        </div>
    @endpermission
@endsection
