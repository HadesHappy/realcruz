<h4 class="mb-3">{{ trans('messages.automation.operation.' . request()->operation) }}</h4>
<p>{{ trans('messages.automation.operation.' .request()->operation. '.desc') }}</p>

<div class="row my-2 hide">
    <div class="col-md-8">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.operation.choose_an_action'),
            'name' => 'options[operation_type]',
            'value' => isset($element) ? $element->getOption('operation_type') : [],
            'options' => [
                ['value' => 'copy', 'text' => trans('messages.operation.action.copy')],
                ['value' => 'move', 'text' => trans('messages.operation.action.move')],
                ['value' => 'delete', 'text' => trans('messages.operation.action.delete')],
            ],
            'rules' => ['options.operation_type' => 'required'],
        ])
    </div>
</div>

<div class="row my-2 operation-list-select" {!! isset($element) && $element->getOption('operation_type') == 'delete' ? 'style="display:none"' : '' !!}>
    <div class="col-md-8">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.operation.target_list'),
            'name' => 'options[target_list_uid]',
            'value' => isset($element) ? $element->getOption('target_list_uid') : [],
            'options' => $automation->mailList->otherLists()->map(function($list) {
                return ['text' => $list->name, 'value' => $list->uid];
            }),
            'rules' => ['options.target_list_uid' => 'required'],
            'include_blank' => trans('messages.choose_a_list'),
        ])
    </div>
</div>

<script>
    customValidate($('#operation-edit'));

    $('.add-more-operation').on('click', function(e) {
        e.preventDefault();

        $('#operation-edit').valid();
    });

    function checkDelete(value) {
        if(value == 'delete') {
            $('.operation-list-select').hide();
        } else {
            $('.operation-list-select').show();
        }
    }
    
    $('[name="options[operation_type]"]').on('change', function(e) {
        e.preventDefault();
        var value = $(this).val();

        checkDelete(value);
    });
</script>
