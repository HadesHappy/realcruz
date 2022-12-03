@include('helpers.form_control', [
    'type' => 'select',
    'class' => 'required',
    'label' => trans('messages.condition.which_email_read'),
    'name' => 'email',
    'value' => $element->getOption('email'),
    'help_class' => 'trigger',
    'include_blank' => trans('messages.automation.condition.choose_email'),
    'required' => true,
    'options' => $automation->getEmailOptions(),
    'rules' => [],
])

@include('automation2.condition._wait')