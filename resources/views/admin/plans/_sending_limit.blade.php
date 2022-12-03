@include('helpers.form_control', [
    'type' => 'select',
    'options' => $plan->getSendingLimitSelectOptions(),
    'class' => '',
    'name' => 'plan[options][sending_limit]',
    'label' => trans('messages.plan.set_a_limit'),
    'value' => $plan->getOption('sending_limit'),
    'help_class' => 'plan',
    'rules' => [],    
])

<input type="hidden" name="plan[options][sending_quota]" value="{{ $plan->getOption('sending_quota') }}" />
<input type="hidden" name="plan[options][sending_quota_time]" value="{{ $plan->getOption('sending_quota_time') }}" />
<input type="hidden" name="plan[options][sending_quota_time_unit]" value="{{ $plan->getOption('sending_quota_time_unit') }}" />

<script>
    $(function() {
        $('[name="plan[options][sending_limit]"]').on('change', function() {
            var val = $(this).val();

            if (val == 'custom') {
                PlansSecurity.getSendingLimitPopup().load();
            }
        });
    });
</script>