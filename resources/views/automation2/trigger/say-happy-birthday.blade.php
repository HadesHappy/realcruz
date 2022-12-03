<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="date_of_birth" />
        
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before'),
            'name' => 'options[before]',
            'value' => $trigger->getOption('before'),
            'help_class' => 'trigger',
            'options' => Acelle\Model\Automation2::getDelayBeforeOptions(),
            'rules' => $rules,
        ])

        @include('helpers.form_control', [
            'type' => 'time2',
            'name' => 'options[at]',
            'label' => trans('messages.automation.at'),
            'value' => ($trigger->getOption('at') ? $trigger->getOption('at') : '10:00 AM'),
            'rules' => $rules,
            'help_class' => 'trigger'
        ])
    </div>
</div>
<p>{{ trans('messages.automation.choose_birthday_field') }}</p>

@if($automation->mailList->getDateOrDateTimeFields()->count())
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => '',
                'include_blank' => trans('messages.automation.choose_list_field'),
                'name' => 'options[field]',
                'value' => $trigger->getOption('field'),
                'help_class' => 'trigger',
                'options' => $automation->mailList->getDateOrDateTimeFields()->get()->map(function($field) {
                    return ['text' => $field->label, 'value' => $field->uid];
                })->toArray(),
                'rules' => $rules,
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
