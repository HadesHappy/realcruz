@php
    $trigger = $automation->getTrigger();
    $items = $trigger->wooGetAbandonedCart();
@endphp

<input type="hidden" name="item_title" value="" />

@include('helpers.form_control', [
    'type' => 'select_ajax',
    'class' => 'required',
    'label' => trans('messages.condition.which_woo_item'),
    'name' => 'item_id',
    'selected' => ['value' => $element->getOption('item_id'), 'text' => $element->getOption('item_title')],
    'help_class' => 'condition',
    'url' => $trigger->getOption('connect_url'),
    'include_blank' => trans('messages.condition.select_woo_item'),
    'required' => true,
    'rules' => [],
])

<script>
    function updateItemTitle() {
        var text = $( "[name=item_id] option:selected" ).text();
        $( "[name=item_title]" ).val(text);
    }
    $( "[name=item_id]" ).on('change', function() {
        updateItemTitle();
    });

    updateItemTitle();
</script>