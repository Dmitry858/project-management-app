@extends('layouts.app')

@section('content')
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('members.store') }}">
        @csrf
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="user_id">
                    @lang('form.label_user')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="user_id" name="user_id">
                    <option value="">@lang('form.empty_select_value')</option>
                    @if(isset($users) && count($users) > 0)
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="project_ids">
                    @lang('form.label_projects')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project_ids" name="project_ids[]" multiple>
                    @if(isset($projects) && count($projects) > 0)
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
    </form>
@endsection
