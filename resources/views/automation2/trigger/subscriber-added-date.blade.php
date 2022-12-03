<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="created_at" />
    
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before_or_after'),
            'name' => 'options[delay]',
            'value' => $trigger->getOption('delay'),
            'help_class' => 'trigger',
            'options' => \Acelle\Model\Automation2::getDelayOptions(),
            'rules' => $rules,
        ])
        
        @include('helpers.form_control', [
            'type' => 'time2',
            'name' => 'options[at]',
            'label' => trans('messages.automation.at'),
            'value' => ($trigger->getOption('at') ? $trigger->getOption('at') : '8:00 AM'),
            'rules' => [],
            'help_class' => 'trigger',
            'rules' => $rules,
        ])
    </div>
</div>