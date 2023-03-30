@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('invitations') }}
    @include('include.flash-success')
    @include('include.flash-error')
    @include('include.toast-success')
    @include('include.toast-error')

    @if (count($invitations) > 0)
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="py-2 inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-300 border-b">
                                <tr>
                                    @include('include.table-th', ['type' => 'checkbox'])
                                    @include('include.table-th', ['text' => __('table.col_secret_key')])
                                    @include('include.table-th', ['text' => __('table.col_email')])
                                    @include('include.table-th', ['text' => __('table.col_created_at')])
                                    @include('include.table-th', ['text' => __('table.col_is_sent')])
                                    @include('include.table-th', ['text' => __('table.col_user')])
                                    @include('include.table-th', ['text' => ''])
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invitations as $invitation)
                                    <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $invitation->id }}" data-entity="invitations">
                                        @include('include.table-td', ['type' => 'checkbox'])
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $invitation->secret_key }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $invitation->email }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            {{ $invitation->created_at->format('d.m.Y H:i:s') }}
                                            @if($invitation->isExpired())
                                                <span class="text-red-500">(@lang('table.is_expired'))</span>
                                            @endif
                                        </td>
                                        <td id="status-{{ $invitation->id }}" class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @if($invitation->is_sent)
                                                @lang('table.status_yes')
                                            @else
                                                @lang('table.status_no')
                                            @endif
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @if($invitation->user_id)
                                                {{ $invitation->user->full_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                            @include('include.buttons.send-invitation', ['link' => route('invitations.send', ['invitation' => $invitation->id]), 'user_id' => $invitation->user_id])
                                            @include('include.buttons.delete', ['link' => route('invitations.destroy', ['invitation' => $invitation->id])])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $invitations->links() }}
    @else
        <p>@lang('empty.invitations')</p>
    @endif

    <div class="flex items-center py-4 mt-2">
        @include('include.buttons.create', ['link' => route('invitations.create')])
        <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
            @lang('buttons.delete_selected')
        </a>
    </div>
@endsection

