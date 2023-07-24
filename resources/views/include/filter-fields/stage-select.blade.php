<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="stage_id">
        @lang('form.label_stage')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="stage_id" name="stage_id">
        <option value="">@lang('form.option_all')</option>
        @if(isset($stages) && count($stages) > 0)
            @foreach($stages as $stage)
                <option value="{{ $stage->id }}" @if($stage->id == request()->get('stage_id')) selected @endif>
                    {{ $stage->name }}
                </option>
            @endforeach
        @endif
    </select>
</div>
