@php
    $options = [
        ['text' => trans('messages.automation.condition.open'), 'value' => 'open'],
        ['text' => trans('messages.automation.condition.click'), 'value' => 'click'],
    ];

    $trigger = $automation->getTrigger();

    if ($trigger->getOption('type') == 'woo-abandoned-cart') {
        $options = array_merge($options, [
            ['text' => trans('messages.automation.condition.cart_buy_anything'), 'value' => 'cart_buy_anything'],
            ['text' => trans('messages.automation.condition.cart_buy_item'), 'value' => 'cart_buy_item'],
        ]);
    }

    $id = uniqid();
@endphp

<h4 class="mb-3">
    {{ trans('messages.automation.action.set_up_your_condition') }}
</h4>
<p class="mb-3">
    {{ trans('messages.automation.action.condition.intro') }}
</p>

<div class="condition-type container-{{ $id }}">
    <div class="mb-20">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => 'Select criterion',
            'name' => 'type',
            'value' => $element->getOption('type'),
            'help_class' => 'trigger',
            'options' => $options,
            'rules' => [],
        ])
    </div>
        
    <div class="condition-setting">
    </div>
</div>
    
<script>

    function showSetting(container) {
        var box = new Box(container.find('.condition-setting'));
        var type = container.find('[name=type]').val();
        var url = '{{ action('Automation2Controller@conditionSetting', [
        'uid' => $automation->uid,
    ]) }}?type=' + type + '&element_id={{ $element->get('id') }}';

        box.load(url);
    }

    // Toggle condition options
    $(document).on('change', '.condition-type [name=type]', function() {
        showSetting($(this).closest('.condition-type'));
    });
    
    $('.container-{{ $id }}').each(function() {
        showSetting($(this));
    });
</script>