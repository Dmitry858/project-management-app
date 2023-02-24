@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('edit-member', $member) }}
    @include('include.flash-error')

    <form class="w-full max-w-lg" method="POST" action="{{ route('members.update', ['member' => $member->id]) }}">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <p class="uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    @lang('form.label_user')
                </p>
                <p class="text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight">
                    {{ $member->getFullName() }}
                </p>
            </div>

            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="project_ids">
                    @lang('form.label_projects')
                </label>
                <select class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project_ids" name="project_ids[]" multiple>
                    @if(isset($projects) && count($projects) > 0)
                        @foreach($projects as $project)
                            @if(!$project->is_active && !$member->projects->contains($project->id))
                                @continue
                            @endif
                            <option
                                value="{{ $project->id }}"
                                @if($member->projects->contains($project->id)) selected @endif
                            >
                                {{ $project->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.save_button')
        </button>
        <a class="inline-block border border-blue-600 hover:bg-blue-600 text-blue-600 hover:text-white font-bold ml-2 py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('members.index') }}">
            @lang('buttons.cancel')
        </a>
    </form>
@endsection
