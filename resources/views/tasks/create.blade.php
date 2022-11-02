@extends('layouts.app')

@section('content')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    @lang('form.label_title')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" value="" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                    @lang('form.label_description')
                </label>
                <textarea class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" name="description" type="text" value=""></textarea>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="project_id">
                    @lang('form.label_project')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project_id" name="project_id">
                    @if(isset($projects) && count($projects) > 0)
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @if($project->id == request()->get('project_id')) selected @endif>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="owner_id">
                    @lang('form.label_owner')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="owner_id" name="owner_id">
                    <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</option>
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="responsible_id">
                    @lang('form.label_responsible')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="responsible_id" name="responsible_id">
                    @if(isset($members) && count($members) > 0)
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">
                                {{ $member->user->name }} {{ $member->user->last_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="stage_id">
                    @lang('form.label_stage')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="stage_id" name="stage_id">
                    @if(isset($stages) && count($stages) > 0)
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_active">
                    @lang('form.label_activity')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_active" name="is_active">
                    <option value="1">@lang('form.status_active')</option>
                    <option value="0">@lang('form.status_archived')</option>
                </select>
            </div>

            <div class="w-full px-3 mb-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="deadline">
                    @lang('form.label_deadline')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="deadline" name="deadline" type="datetime-local" value="">
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
    </form>
@endsection
