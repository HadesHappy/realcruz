<div class="mb-20">
    <input type="hidden" name="options[type]" value="datetime" />
    
    @php
        $customer = Auth::user()->customer;

        $date = $trigger->getOptionAsDateTime('date')->timezone(Auth::user()->customer->timezone)->format('Y-m-d');
        if (!$date) {            
            $date = $customer->getCurrentTime()->format('Y-m-d');
        }

        $time = $trigger->getOptionAsDateTime('at')->timezone(Auth::user()->customer->timezone)->format('H:i');
        if (!$time) {
            $time = $customer->getCurrentTime()->format('H:i');
        }
    @endphp

    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => trans('messages.automation.date'),
        'name' => 'options[date]',
        'value' => $date,
        'help_class' => 'trigger',
        'rules' => $rules,
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => trans('messages.automation.at'),
        'value' => $time,
        'rules' => $rules,
        'help_class' => 'trigger'
    ])
</div>