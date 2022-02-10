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
                                            {{ $project->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $project->description }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $project->is_active ? 'Активный' : 'В архиве' }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @include('include.buttons.edit', ['link' => route('projects.edit', ['project' => $project->id])])
                                            @include('include.buttons.delete', ['link' => route('projects.destroy', ['project' => $project->id])])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>@lang('empty.projects')</p>
    @endif

    <div class="py-4 mt-4">
        @include('include.buttons.create', ['link' => route('projects.create')])
    </div>
@endsection
