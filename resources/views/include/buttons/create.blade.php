<a href="{{ $link }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    @if (isset($label))
        @lang('buttons.'.$label)
    @else
        @lang('buttons.create')
    @endif
</a>
