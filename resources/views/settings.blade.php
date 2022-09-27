@extends('layouts.app')

@section('content')
    @include('include.flash-success')
    @include('include.flash-error')

    <div class="w-full md:w-1/2 xl:w-1/4 mt-2 mb-8">
        <div class="relative bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-5">
            <div class="flex flex-row items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-5 bg-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                            <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0zM2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5v-1zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5v-1zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-right md:text-center">
                    <h2 class="font-bold uppercase text-gray-700">Стадии задач</h2>
                    <a class="absolute top-0 bottom-0 left-0 right-0" href="{{ route('stages.index') }}"></a>
                </div>
            </div>
        </div>
    </div>

    <form class="w-full max-w-lg" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        <input class="hidden" type="checkbox" name="clear_cache" value="1" checked>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            @lang('form.clear_cache_button')
        </button>
    </form>
@endsection
