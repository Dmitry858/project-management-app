@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-between items-center breadcrumbs-wrap">
        {{ Breadcrumbs::render('calendar') }}
        <a href="{{ route('events.index') }}" class="mb-4 text-gray-800 view-selector" title="{{ __('buttons.list_view') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">
                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
            </svg>
        </a>
    </div>
    <div class="error-message text-red-500 text-sm mb-2"></div>
    <header class="calendar-header flex flex-wrap justify-between items-center">
        <nav class="calendar-navbar flex mb-4">
            <select class="appearance-none bg-gray-100 border border-gray-300 hover:bg-blue-100 hover:cursor-pointer rounded-full py-2 px-4 mr-2 transition focus:outline-none" id="calendar-view" name="calendar-view">
                <option value="month">@lang('calendar.month')</option>
                <option value="week" selected>@lang('calendar.week')</option>
                <option value="day">@lang('calendar.day')</option>
            </select>
            <button class="button rounded-full py-2 px-4 mr-2 border border-gray-300 hover:bg-blue-100 transition today">@lang('buttons.today')</button>
            <button class="button flex justify-center items-center rounded-full p-2 mr-2 border border-gray-300 hover:bg-blue-100 transition prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
            </button>
            <button class="button flex justify-center items-center rounded-full p-2 border border-gray-300 hover:bg-blue-100 transition next">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </button>
        </nav>

        <div id="date-range" class="mb-4"></div>

        <div class="calendar-filter flex mb-4">
            <div class="form-check flex items-center mr-3">
                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-1 cursor-pointer" type="checkbox" name="filter[]" value="private" id="private_events" checked>
                <label class="form-check-label inline-block text-gray-800" for="private_events">
                    @lang('calendar.private_events')
                </label>
            </div>
            <div class="form-check flex items-center mr-3">
                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-1 cursor-pointer" type="checkbox" name="filter[]" value="public" id="public_events" checked>
                <label class="form-check-label inline-block text-gray-800" for="public_events">
                    @lang('calendar.public_events')
                </label>
            </div>
            <div class="form-check flex items-center">
                <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left mr-1 cursor-pointer" type="checkbox" name="filter[]" value="project" id="project_events" checked>
                <label class="form-check-label inline-block text-gray-800" for="project_events">
                    @lang('calendar.project_events')
                </label>
            </div>
        </div>
    </header>
    <div id="calendar"></div>
@endsection

@push('additional-styles')
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
@endpush

@push('additional-scripts')
    <script>
        let locale = '{{ config('app.locale') }}',
            timezoneName = '{{ config('calendar.timezoneName') }}',
            calendars = <?= json_encode($calendars) ?>,
            permissions = <?= json_encode($permissions) ?>;
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush
