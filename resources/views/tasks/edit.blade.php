@extends('layouts.app')

@section('content')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    @lang('form.label_title')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" name="name" type="text" value="{{ $task->name }}" required>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                    @lang('form.label_description')
                </label>
                <textarea class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" name="description" type="text">{{ $task->description }}</textarea>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="project_id">
                    @lang('form.label_project')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project_id" name="project_id">
                    @if(isset($projects) && count($projects) > 0)
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @if($task->project_id === $project->id) selected @endif>{{ $project->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="owner_id">
                    @lang('form.label_owner')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="owner_id" name="owner_id">
                    @if(isset($members) && count($members) > 0)
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @if($task->owner_id === $member->id) selected @endif>
                                {{ $task->getMemberFullName($member->id) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="responsible_id">
                    @lang('form.label_responsible')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="responsible_id" name="responsible_id">
                    @if(isset($members) && count($members) > 0)
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @if($task->responsible_id === $member->id) selected @endif>
                                {{ $task->getMemberFullName($member->id) }}
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
                            <option value="{{ $stage->id }}" @if($task->stage_id === $stage->id) selected @endif>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_active">
                    @lang('form.label_activity')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="is_active" name="is_active">
                    <option value="1" @if($task->is_active) selected @endif>@lang('form.status_active')</option>
                    <option value="0" @if(!$task->is_active) selected @endif>@lang('form.status_archived')</option>
                </select>
            </div>

            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="deadline">
                    @lang('form.label_deadline')
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="deadline" name="deadline" type="datetime-local" value="{{ $task->deadline }}">
            </div>

            <div class="w-full px-3 mb-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="attachments">
                    @lang('form.label_attachments')
                </label>
                @if(count($task->attachments) > 0)
                    <input type="hidden" id="deleted_attachments" name="deleted_attachments" value="">
                    @foreach($task->attachments as $attachment)
                        @include('include.attachment', ['template' => 'edit'])
                    @endforeach
                    <details class="text-gray-700">
                        <summary>@lang('form.add_new_files')</summary>
                        <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="attachments" name="attachments[]" type="file" multiple accept="image/*, application/pdf, application/zip, application/msword, .xls, .xlsx, text/plain">
                    </details>
                @else
                    <input class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="attachments" name="attachments[]" type="file" multiple accept="image/*, application/pdf, application/zip, application/msword, .xls, .xlsx, text/plain">
                @endif
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('tasks.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
