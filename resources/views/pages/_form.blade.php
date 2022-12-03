@include('helpers.form_control', [
    'type' => 'text',
    'name' => 'subject',
    'value' => $page->subject,
    'rules' => ['subject' => 'subject']])

@include('helpers.form_control', ['class' => ($layout->type == 'page' ? 'full-editor' : 'email-editor'), 'type' => 'textarea', 'name' => 'content', 'value' => $page->content, 'rules' => $list->getFieldRules()])

@if (count($layout->tags()) > 0)
    <div class="tags_list">
        <label class="text-semibold text-teal">{{ trans('messages.required_tags') }}:</label>
        <br />
        @foreach($layout->tags() as $tag)
            @if ($tag["required"])
                <a data-popup="tooltip" draggable="false" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button" data-tag-name="{{ $tag["name"] }}">
                    {{ $tag["name"] }}
                </a>
            @endif
        @endforeach
    </div>
@endif

<br />
@if (count($layout->tags()) > 0)
    <div class="tags_list">
        <label class="text-semibold text-teal">{{ trans('messages.available_tags') }}:</label>
        <br />
        @foreach ($list->fields as $field)
            <a data-popup="tooltip" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button"
                data-tag-name="{{ "{SUBSCRIBER_".$field->tag."}" }}">
                {{ "{SUBSCRIBER_".$field->tag."}" }}
            </a>
        @endforeach
        @foreach($layout->tags() as $tag)
            @if (!$tag["required"])
                <a data-popup="tooltip" draggable="false" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button" data-tag-name="{{ $tag["name"] }}">
                    {{ $tag["name"] }}
                </a>
            @endif
        @endforeach
    </div>
@endif

<script>
    $(function() {
        // Click to insert tag
        $(document).on("click", ".insert_tag_button", function() {
            var tag = $(this).attr("data-tag-name");

            if($('textarea[name="html"]').length || $('textarea[name="content"]').length) {
                tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
            } else {
                speechSynthesis;
                $('textarea[name="plain"]').val($('textarea[name="plain"]').val()+tag);
            }
        });
    });
</script>