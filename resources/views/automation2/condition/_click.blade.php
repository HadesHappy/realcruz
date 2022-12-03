@include('helpers.form_control', [
    'type' => 'select',
    'class' => 'required',
    'label' => 'Which Link subscriber clicks',
    'name' => 'email_link',
    'value' => $element->getOption('email_link'),
    'help_class' => 'trigger',
    'options' => $automation->getEmailLinkOptions(),
    'include_blank' => trans('messages.automation.condition.choose_link'),
    'required' => true,
    'rules' => [],
])

@include('automation2.condition._wait')