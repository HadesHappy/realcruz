<div class="mb-20">
    <input type="hidden" name="options[type]" value="woo-abandoned-cart" />
    
    <div class="edit-connect-url">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => '',
            'name' => 'options[source_uid]',
            'value' => '',
            'options' => request()->user()->customer->getSelectOptions('woocommerce'),
            'help_class' => 'trigger',
            'rules' => [],
        ])
        <input type="hidden" name="options[wait]" value="24_hour" />
    </div>

    @include('helpers.form_control', [
        'name' => 'mail_list_uid',
        'include_blank' => trans('messages.automation.choose_list'),
        'type' => 'select',
        'label' => trans('messages.list'),
        'value' => '',
        'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
    ])
</div>
