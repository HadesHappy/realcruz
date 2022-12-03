<div class="mb-20">
    <input type="hidden" name="options[type]" value="datetime" />

    @php
        $customer = Auth::user()->customer;
        $date = $customer->getCurrentTime()->format('Y-m-d');
        $time = $customer->getCurrentTime()->format('H:i');
    @endphp

    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => trans('messages.automation.date'),
        'name' => 'options[date]',
        'value' => $date,
        'help_class' => 'trigger',
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => trans('messages.automation.at'),
        'value' => $time,
        'help_class' => 'trigger'
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