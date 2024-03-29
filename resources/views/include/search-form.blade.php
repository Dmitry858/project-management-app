<div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-white px-2">
    <span class="relative w-full">
        <form method="GET" action="{{ route('tasks.search') }}">
            <div class="absolute search-icon" style="top: 0.8rem; left: 0.8rem;">
                <svg class="fill-current pointer-events-none text-white w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                </svg>
            </div>
            <input aria-label="search" type="text" id="search" name="phrase" placeholder="{{ __('form.placeholder_tasks_search') }}" class="w-full bg-gray-900 text-white transition border border-transparent focus:outline-none focus:border-gray-400 rounded py-2 px-2 pl-10 appearance-none leading-normal">
        </form>
    </span>
</div>
