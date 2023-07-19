<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="event_type">
        @lang('table.col_event_type')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 pl-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="event_type" name="event_type">
        <option value="">@lang('form.empty_select_value')</option>
        <option value="private" @if(request()->get('event_type') === 'private') selected @endif>
            @lang('calendar.private')
        </option>
        <option value="public" @if(request()->get('event_type') === 'public') selected @endif>
            @lang('calendar.public')
        </option>
        <option value="project" @if(request()->get('event_type') === 'project') selected @endif>
            @lang('calendar.project_unnamed')
        </option>
    </select>
</div>
