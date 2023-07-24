<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="event_start">
        @lang('table.col_start')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 pl-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="event_start" name="event_start">
        <option value="">@lang('form.empty_value')</option>
        <option value="today" @if(request()->get('event_start') === 'today') selected @endif>
            @lang('form.option_today')
        </option>
        <option value="tomorrow" @if(request()->get('event_start') === 'tomorrow') selected @endif>
            @lang('form.option_tomorrow')
        </option>
        <option value="week" @if(request()->get('event_start') === 'week') selected @endif>
            @lang('form.option_week')
        </option>
        <option value="month" @if(request()->get('event_start') === 'month') selected @endif>
            @lang('form.option_month')
        </option>
    </select>
</div>
