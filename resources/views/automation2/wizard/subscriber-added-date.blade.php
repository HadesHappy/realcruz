<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="created_at" />
    
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before_or_after'),
            'name' => 'options[delay]',
            'value' => '',
            'help_class' => 'trigger',
            'options' => \Acelle\Model\Automation2::getDelayOptions(),
        ])
        
        @include('helpers.form_control', [
            'type' => 'time2',
            'name' => 'options[at]',
            'label' => trans('messages.automation.at'),
            'value' => '8:00 AM',
            'rules' => [],
            'help_class' => 'trigger',
        ])

        @include('helpers.form_control', [
            'name' => 'mail_list_uid',
            'include_blank' => trans('messages.automation.choose_list'),
            'type' => 'select',
            'label' => trans('messages.list'),
            'value' => '',
            'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
        ])
    </div>
</div>