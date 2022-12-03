<p class="mb-0 mt-2">{{ trans('messages.automation.choose_birthday_field') }}:</p>

@if($list->getDateOrDateTimeFields()->count())
    <div class="row">
        <div class="col-md-6 mt-2">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => '',
                'include_blank' => trans('messages.automation.choose_list_field'),
                'name' => 'options[field]',
                'value' => '',
                'help_class' => 'trigger',
                'options' => $list->getDateOrDateTimeFields()->get()->map(function($field) {
                    return ['text' => $field->label, 'value' => $field->uid];
                })->toArray(),
            ])
        </div>
    </div>
@else
    <div class="mt-2">
        @include('elements._notification', [
            'level' => 'warning',
            'message' => trans('messages.list.no_date_or_datetime_field'),
        ])
    </div>        
@endif