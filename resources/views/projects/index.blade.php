@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($projects) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['text' => 'ID'])
                                    @include('include.table-th', ['text' => 'Название'])
                                    @include('include.table-th', ['text' => 'Описание'])
                                    @include('include.table-th', ['text' => 'Участники'])
                                    @include('include.table-th', ['text' => 'Активность'])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $project->id }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('projects.show', ['project' => $project->id]) }}">{{ $project->name }}</a>
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $project->description }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $project->members->count() }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $project->is_active ? 'Активный' : 'В архиве' }}
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
