<h4 class="mb-3">{{ trans('messages.operation.tag_contact') }}</h4>
<p>{{ trans('messages.operation.tag_contact.desc') }}</p>

<input type="hidden" name="options[operation_type]" value="tag" />

@include('helpers.form_control', [
    'type' => 'select_tag',
    'class' => '',
    'label' => '',
    'name' => 'options[tags][]',
    'value' => isset($element) ? $element->getOption('tags') : [],
    'help_class' => 'trigger',
    'options' => array_map(function($tag) {
            return ['text' => $tag, 'value' => $tag];
        }, isset($element) ? $element->getOption('tags') : []
    ),
    'rules' => ['tags' => 'required'],
    'multiple' => 'true',
    'placeholder' => trans('messages.automation.contact.choose_tags'),
    'rules' => ['options.tags' => 'required'],
])

<script>
    customValidate($('#operation-edit'));

    $('.add-more-operation').on('click', function(e) {
        e.preventDefault();

        $('#operation-edit').valid();
    });
</script>