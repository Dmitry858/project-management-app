<header>
    <div class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">
        <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                <a href="{{ route('dashboard') }}" aria-label="Home">
                    @if(Storage::disk('public')->exists('logo.png'))
                        <img src="{{ asset('storage/logo.png') }}" class="max-h-9 max-w-xs pl-2" alt="{{ __('header.logo') }}">
                    @else
                        <span class="text-3xl pl-2">@lang('header.logo')</span>
                    @endif
                </a>
            </div>

            @include('include.search-form')

            <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li class="flex-1 md:flex-none md:mr-3">
                        <div class="relative inline-block">
                            <button id="user-dropdown-trigger" class="drop-button text-white py-2 px-2">
                                <span class="pr-2"><i class="em em-robot_face"></i></span>
                                @lang('header.greeting'), {{ Auth::user()->name }}
                                <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                            </button>
                            <div id="user-dropdown" class="dropdownlist absolute bg-gray-800 text-white right-0 p-3 overflow-auto z-30 invisible">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block">
                                        @lang('header.logout')
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
