@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('single-event', $event) }}
    <div class="flex flex-wrap -mx-3 mt-4 mb-6 w-full max-w-lg">
        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('form.label_title')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                {{ $event->title }}
            </p>
        </div>

        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('table.col_event_type')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                {{ $event->type() }}
            </p>
        </div>

        <div class="flex flex-wrap w-full px-3 mb-6">
            <div class="w-full md:w-1/2 md:pr-3 mb-6 md:mb-0">
                <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    @lang('table.col_start')
                </p>
                <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                    {{ $event->formattedStart() }}
                </p>
            </div>
            <div class="w-full md:w-1/2 md:pl-3">
                <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    @lang('table.col_end')
                </p>
                <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                    {{ $event->formattedEnd() }}
                </p>
            </div>
        </div>

        <div class="w-full px-3 mb-6">
            <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                @lang('table.col_allday')
            </p>
            <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                @if($event->is_allday)
                    @lang('table.status_yes')
                @else
                    @lang('table.status_no')
                @endif
            </p>
        </div>

        <div class="w-full px-3 mt-3">
            <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('events.index') }}">
                @lang('buttons.back_to_list')
            </a>
        </div>
    </div>
@endsection
