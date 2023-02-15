<a href="/{{ $entity }}/{{ $entityObj->id }}">
    <div class="flex items-center justify-between w-full p-2 bg-white hover:bg-blue-100 transition cursor-pointer border rounded-lg border-gray-300 my-2 min-h-50">
        <div class="lg:flex md:flex items-center ml-2">
            <div class="flex flex-col">
                <div class="text-sm leading-4 text-gray-700 font-bold w-full">
                    {{ $entityObj->name }}
                </div>
                <div class="text-xs text-gray-600 w-full">
                    {{ $entityObj->description }}
                </div>
            </div>
        </div>
        <svg class="h-6 w-6 mr-1 invisible md:visible lg:visible xl:visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </div>
</a>
