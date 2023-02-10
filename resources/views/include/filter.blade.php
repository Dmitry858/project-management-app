<form class="w-full filter" method="GET" action="">
    @csrf
    <div class="flex flex-wrap items-center -mx-3 mb-3">
        <div class="flex items-center w-full sm:w-auto mb-2 sm:mb-0" title="{{ __('titles.filter') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#374151" class="bi bi-funnel ml-3" viewBox="0 0 16 16">
                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
            </svg>
            <h4 class="text-gray-700 text-xl ml-2 sm:hidden">@lang('titles.filter')</h4>
        </div>

        @if($entity === 'tasks')
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
        @endif

        @if($entity === 'tasks')
            @permission('view-all-tasks')
                @include('include.filter-activity-select')
            @endpermission
        @else
            @include('include.filter-activity-select')
        @endif

        <button class="filter-apply-btn bg-blue-600 hover:bg-blue-700 text-white font-bold mx-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('buttons.apply')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-3 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route($entity.'.index') }}">
            @lang('buttons.reset')
        </a>
    </div>
</form>