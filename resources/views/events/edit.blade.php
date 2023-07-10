@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('edit-event', $event) }}
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('events.update', ['event' => $event->id]) }}">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                    @lang('form.label_title')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="title" name="title" type="text" value="{{ $event->title }}" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="event_type">
                    @lang('table.col_event_type')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="event_type" name="event_type">
                    <option value="private">@lang('calendar.private')</option>
                    @permission('edit-events-of-projects-and-public-events')
                        <option value="public" @if(!$event->is_private && !$event->project_id) selected @endif>@lang('calendar.public')</option>
                        @if(isset($projects) && count($projects) > 0)
                            @foreach($projects as $project)
                                <option value="{{ $project['id'] }}" @if(!$event->is_private && $event->project_id == str_replace('project_', '', $project['id'])) selected @endif>{{ $project['name'] }}</option>
                            @endforeach
                        @endif
                    @endpermission
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start">
                    @lang('table.col_start')
                </label>
                <input class="date-input appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="start" name="start" @if($event->is_allday) type="date" @else type="datetime-local" @endif value="@php echo $event->is_allday ? substr($event->start, 0, 10) : $event->start @endphp" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end">
                    @lang('table.col_end')
                </label>
                <input class="date-input appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="end" name="end" @if($event->is_allday) type="date" @else type="datetime-local" @endif value="@php echo $event->is_allday ? substr($event->end, 0, 10) : $event->end @endphp" required>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_allday">
                    @lang('table.col_allday')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_allday" name="is_allday">
                    <option value="0" @if(!$event->is_allday) selected @endif>@lang('table.status_no')</option>
                    <option value="1" @if($event->is_allday) selected @endif>@lang('table.status_yes')</option>
                </select>
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('buttons.save')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('events.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
