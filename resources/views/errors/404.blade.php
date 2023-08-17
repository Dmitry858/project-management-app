<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
        <h1 class="text-3xl">@lang('errors.404_error')</h1>
        <p>@lang('errors.page_not_found')</p>
        <a href="/" class="mt-4 bg-blue-600 hover:bg-blue-700 transition text-xs text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline uppercase">
            @lang('buttons.home')
        </a>
    </div>
</x-guest-layout>
