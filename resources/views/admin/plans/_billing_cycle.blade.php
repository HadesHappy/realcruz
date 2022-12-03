@include('helpers.form_control', [
    'type' => 'select',
    'options' => $plan->getBillingCycleSelectOptions(),
    'class' => '',
    'name' => 'plan[options][billing_cycle]',
    'label' => trans('messages.plan.billing_cycle'),
    'value' => $plan->getOption('billing_cycle'),
    'help_class' => 'plan',
    'rules' => [],    
])

<input type="hidden" name="plan[general][frequency_amount]" value="{{ $plan->frequency_amount }}" />
<input type="hidden" name="plan[general][frequency_unit]" value="{{ $plan->frequency_unit }}" />