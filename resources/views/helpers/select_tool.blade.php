<select name='select_tool' class="select select_tool">
    <option value=''>{{ trans('messages.select_tool.select') }}</option>
    <option value="whole_page">{{ trans('messages.select_tool.whole_page') }}</option>
    @if (!isset($disable_all_items) || !$disable_all_items)
        <option value="all_items">{{ trans('messages.select_tool.all_items') }}</option>
    @endif
</select>
