<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_active">
        @lang('form.label_activity')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_active" name="is_active">
        <option value="">@lang('form.option_all')</option>
        <option value="1" @if(request()->get('is_active') === '1') selected @endif>
            @lang('form.status_active')
        </option>
        <option value="0" @if(request()->get('is_active') === '0') selected @endif>
            @lang('form.status_archived')
        </option>
    </select>
</div>
