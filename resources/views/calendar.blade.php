@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('calendar') }}
    <div class="error-message text-red-500 text-sm mb-2"></div>
    <header class="calendar-header">
        <nav class="calendar-navbar flex mb-4">
            <select class="appearance-none bg-gray-100 border border-gray-300 hover:bg-blue-100 hover:cursor-pointer rounded-full py-3 px-4 mr-2 transition focus:outline-none" id="calendar-view" name="calendar-view">
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
