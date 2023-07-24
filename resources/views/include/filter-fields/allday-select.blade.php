<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_allday">
        @lang('table.col_allday')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 pl-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_allday" name="is_allday">
        <option value="">@lang('form.empty_select_value')</option>
        <option value="1" @if(request()->get('is_allday') === '1') selected @endif>
            @lang('table.status_yes')
        </option>
        <option value="0" @if(request()->get('is_allday') === '0') selected @endif>
            @lang('table.status_no')
        </option>
    </select>
</div>
