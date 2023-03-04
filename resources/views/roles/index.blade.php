@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('roles') }}
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($roles) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table>
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['type' => 'checkbox'])
                                    @include('include.table-th', ['text' => __('table.col_title')])
                                    @include('include.table-th', ['text' => __('table.col_slug')])
                                    @include('include.table-th', ['text' => __('table.col_permissions')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $role->id }}" data-entity="roles">
                                        @include('include.table-td', ['type' => 'checkbox'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('roles.show', ['role' => $role->id]) }}" title="{{ $role->name }}">
                                                {{ $role->name }}
                                            </a>
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $role->slug }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ count($role->permissions) }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap @if($role->id === 1) is-admin @endif">
                                            @include('include.buttons.edit', ['link' => route('roles.edit', ['role' => $role->id])])
                                            @include('include.buttons.delete', ['link' => route('roles.destroy', ['role' => $role->id])])
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
        <p>@lang('empty.roles')</p>
    @endif

    <div class="py-4 mt-4">
        @include('include.buttons.create', ['link' => route('roles.create')])
        <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
            @lang('buttons.delete_selected')
        </a>
    </div>
@endsection

