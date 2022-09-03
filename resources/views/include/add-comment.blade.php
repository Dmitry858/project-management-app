@csrf
<textarea class="appearance-none block w-full text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="comment" name="comment" placeholder="{{ __('form.placeholder_add_comment') }}" data-task-id="{{ $task->id }}" value=""></textarea>
<div class="flex mt-4">
    <a id="add-comment-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-1 px-3 rounded focus:outline-none focus:shadow-outline" href="#">
        @lang('buttons.send')
    </a>
    <span id="add-comment-error" class="text-red-500 ml-2"></span>
</div>
