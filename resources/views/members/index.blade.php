@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('members') }}
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($members) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @permission('delete-members')
                                        @include('include.table-th', ['type' => 'checkbox'])
                                    @endpermission
                                    @include('include.table-th', ['text' => 'Имя'])
                                    @include('include.table-th', ['text' => 'Фамилия'])
                                    @include('include.table-th', ['text' => 'Проекты'])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $member->id }}" data-entity="members">
                                        @permission('delete-members')
                                            @include('include.table-td', ['type' => 'checkbox'])
                                        @endpermission
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $member->user->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $member->user->last_name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ count($member->projects) }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @permission('edit-members')
                                                @include('include.buttons.edit', ['link' => route('members.edit', ['member' => $member->id])])
                                            @endpermission
                                            @permission('delete-members')
                                                @include('include.buttons.delete', ['link' => route('members.destroy', ['member' => $member->id])])
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
        {{ $members->links() }}
    @else
        <p>@lang('empty.members')</p>
    @endif

    <div class="flex items-center py-4 mt-2">
        @permission('create-members')
            @include('include.buttons.create', ['link' => route('members.create'), 'label' => 'add'])
        @endpermission
        @permission('delete-members')
            <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
                @lang('buttons.delete_selected')
            </a>
        @endpermission
    </div>
@endsection

