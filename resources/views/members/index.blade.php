@extends('layouts.app')

@section('content')
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
                                    @include('include.table-th', ['text' => 'Имя'])
                                    @include('include.table-th', ['text' => 'Фамилия'])
                                    @include('include.table-th', ['text' => 'Проекты'])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr class="bg-white border-b">
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
                                            @include('include.buttons.edit', ['link' => route('members.edit', ['member' => $member->id])])
                                            @include('include.buttons.delete', ['link' => route('members.destroy', ['member' => $member->id])])
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
        <p>@lang('empty.members')</p>
    @endif

    <div class="py-4 mt-4">
        @include('include.buttons.create', ['link' => route('members.create'), 'label' => 'add'])
    </div>
@endsection

