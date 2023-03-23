@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('users') }}
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.filter', ['entity' => 'users'])

    @if (count($users) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @permission('delete-users')
                                        @include('include.table-th', ['type' => 'checkbox'])
                                    @endpermission
                                    @include('include.table-th', ['text' => __('table.col_photo')])
                                    @include('include.table-th', ['text' => __('table.col_name')])
                                    @include('include.table-th', ['text' => __('table.col_last_name')])
                                    @include('include.table-th', ['text' => __('table.col_email')])
                                    @include('include.table-th', ['text' => __('table.col_roles')])
                                    @include('include.table-th', ['text' => __('form.label_activity')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $user->id }}" data-entity="users">
                                        @permission('delete-users')
                                            @include('include.table-td', ['type' => 'checkbox'])
                                        @endpermission
                                        <td class="px-6 py-4">
                                            <img class="w-24" src="{{ $user->photoSrc() }}" alt="{{ $user->name }}">
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $user->last_name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $user->email }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4">
                                            @foreach($user->roles as $role)
                                                <p>{{ $role->name }}</p>
                                            @endforeach
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $user->is_active ? __('form.status_active') : __('form.status_archived') }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            <div class="flex">
                                                @permission('edit-users')
                                                    @include('include.buttons.edit', ['link' => route('users.edit', ['user' => $user->id])])
                                                @endpermission
                                                @permission('delete-users')
                                                    @include('include.buttons.delete', ['link' => route('users.destroy', ['user' => $user->id])])
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
        {{ $users->links() }}
    @else
        <p>@lang('empty.users')</p>
    @endif

    <div class="flex items-center py-4 mt-2">
        @permission('create-users')
            @include('include.buttons.create', ['link' => route('users.create')])
        @endpermission
        @permission('delete-users')
            <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
                @lang('buttons.delete_selected')
            </a>
        @endpermission
    </div>
@endsection
