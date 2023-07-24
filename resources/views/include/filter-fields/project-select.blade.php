<div class="max-w-xs px-3 mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="project_id">
        @lang('form.label_project')
    </label>
    <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project_id" name="project_id">
        <option value="">@lang('form.option_all')</option>
        @if(isset($projects) && count($projects) > 0)
            @foreach($projects as $project)
                <option value="{{ $project->id }}" @if($project->id == request()->get('project_id')) selected @endif>{{ $project->name }}</option>
            @endforeach
        @endif
    </select>
</div>
