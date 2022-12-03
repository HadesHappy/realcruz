@include('helpers.form_control', [
    'type' => 'select',
    'class' => 'required',
    'label' => '', 
    'name' => 'wait',
    'value' => request()->wait_amount . ' ' . request()->wait_unit . (request()->wait_amount > 1 ? 's' : ''),
    'required' => true,
    'options' => \Acelle\Model\Automation2::getConditionWaitOptions(request()->wait_amount . ' ' . request()->wait_unit . (request()->wait_amount > 1 ? 's' : '')),
])