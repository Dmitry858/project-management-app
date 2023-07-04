@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-between items-center breadcrumbs-wrap">
        {{ Breadcrumbs::render('events') }}
        <a href="{{ route('calendar') }}" class="mb-4 text-gray-800 view-selector" title="{{ __('buttons.calendar_view') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
            </svg>
        </a>
    </div>
    @include('include.flash-success')
    @include('include.flash-error')

    @if (count($events) > 0)
        <div class="flex flex-col overflow-x-auto">
            <div class="py-2 min-w-full">
                <table class="min-w-full">
                    <thead class="bg-blue-300 border-b">
                        <tr>
                            @include('include.table-th', ['type' => 'checkbox'])
                            @include('include.table-th', ['text' => __('table.col_title')])
                            @include('include.table-th', ['text' => __('table.col_event_type')])
                            @include('include.table-th', ['text' => __('table.col_start')])
                            @include('include.table-th', ['text' => __('table.col_end')])
                            @include('include.table-th', ['text' => __('table.col_allday')])
                            @include('include.table-th', ['text' => ''])
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr class="bg-white border-b hover:bg-blue-100 transition" data-id="{{ $event->id }}" data-entity="events">
                                @include('include.table-td', ['type' => 'checkbox'])
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('events.show', ['event' => $event->id]) }}" title="{{ $event->title }}">{{ Str::limit($event->title, 40) }}</a>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $event->type() }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $event->formattedStart() }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $event->formattedEnd() }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    @if($event->is_allday)
                                        @lang('table.status_yes')
                                    @else
                                        @lang('table.status_no')
                                    @endif
                                </td>
                                <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    @include('include.buttons.edit', ['link' => route('events.edit', ['event' => $event->id])])
                                    @include('include.buttons.delete', ['link' => route('events.destroy', ['event' => $event->id])])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $events->links() }}
    @else
        <p>@lang('empty.events')</p>
    @endif

    <div class="flex items-center py-4 mt-2">
        @include('include.buttons.create', ['link' => route('events.create')])

        <a href="#" id="delete-items-link" class="ml-4 text-gray-900 text-sm font-medium hidden">
            @lang('buttons.delete_selected')
        </a>
    </div>
@endsection
