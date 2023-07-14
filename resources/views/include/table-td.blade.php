<td class="text-sm text-gray-900 px-6 py-4 text-left @if(isset($type) && $type === 'checkbox') w-4 pr-0 @endif">
    @if(isset($type) && $type === 'checkbox')
        <div class="form-check">
            <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm @if(isset($disabled) && $disabled === true) bg-gray-100 @else bg-white @endif checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" type="checkbox" name="mark-deleted" value="" @if(isset($disabled) && $disabled === true) disabled @endif>
        </div>
    @endif
</td>
