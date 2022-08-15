@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($users) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['text' => 'Фото'])
                                    @include('include.table-th', ['text' => 'Имя'])
                                    @include('include.table-th', ['text' => 'Фамилия'])
                                    @include('include.table-th', ['text' => 'Email'])
                                    @include('include.table-th', ['text' => __('form.label_activity')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-white border-b">
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
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $user->is_active ? 'Активный' : 'В архиве' }}
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @permission('edit-users')
                                                @include('include.buttons.edit', ['link' => route('users.edit', ['user' => $user->id])])
                                            @endpermission
                                            @permission('delete-users')
                                                @include('include.buttons.delete', ['link' => route('users.destroy', ['user' => $user->id])])
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
        {{ $users->links() }}
    @else
        <p>@lang('empty.users')</p>
    @endif

    @permission('create-users')
        <div class="py-4 mt-4">
            @include('include.buttons.create', ['link' => route('users.create')])
        </div>
    @endpermission
@endsection
