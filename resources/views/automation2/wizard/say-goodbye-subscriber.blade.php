@include('helpers.form_control', [
    'name' => 'mail_list_uid',
    'include_blank' => trans('messages.automation.choose_list'),
    'type' => 'select',
    'label' => trans('messages.list'),
    'value' => '',
    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
])