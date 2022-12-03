@include('helpers.form_control', [
    'type' => 'text',
    'name' => 'subject',
    'value' => $layout->subject,
    'rules' => ['subject' => 'subject']])

@include('helpers.form_control', ['class' => ($layout->type == 'page' ? 'full-editor' : 'email-editor'), 'required' => true, 'type' => 'textarea', 'name' => 'content', 'value' => $layout->content, 'rules' => ['content' => 'required']])

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
        @foreach($layout->tags() as $tag)
            @if (!$tag["required"])
                <a data-popup="tooltip" draggable="false" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button" data-tag-name="{{ $tag["name"] }}">
                    {{ $tag["name"] }}
                </a>
            @endif
        @endforeach
    </div>
@endif

<div class="text-end">
    <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
    <a href="{{ action('Admin\LayoutController@index') }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
</div>
