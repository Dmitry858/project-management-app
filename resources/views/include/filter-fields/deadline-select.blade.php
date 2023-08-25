<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="deadline">
        @lang('table.col_deadline')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 pl-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="deadline" name="deadline">
        <option value="">@lang('form.option_all')</option>
        <option value="today" @if(request()->get('deadline') === 'today') selected @endif>
            @lang('form.option_today')
        </option>
        <option value="tomorrow" @if(request()->get('deadline') === 'tomorrow') selected @endif>
            @lang('form.option_tomorrow')
        </option>
    </select>
</div>
