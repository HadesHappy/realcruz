@if (count($list->getSegmentSelectOptions()))
    @include('helpers.form_control', [
        'value' => '',
        'type' => 'select',
        'name' => 'segment_uid[]',
        'label' => trans('messages.automation.segment'),
        'value' => $automation->getSegmentUids(),
        'options' => $list->getSegmentSelectOptions(),
        'quick_note' => trans('messages.leave_empty_to_choose_all_list'),
        'placeholder' => trans('messages.automation.segment.all_list'),
        'multiple' => true
    ])
@endif