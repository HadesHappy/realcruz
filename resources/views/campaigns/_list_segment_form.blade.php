<?php $index = isset($index) ? $index : '__index__' ?>

<div class="condition-line" rel="{{ $index }}">
    <div class="row list-segment-container">
        <div class="col-md-2">
            <div class="form-group">
                <label>{{ trans('messages.default_list_information') }}</label>
                <div>
                    <input type='hidden' name="lists_segments[{{ $index }}][is_default]" value="false" />
                    @include('helpers.form_control', [
                        'include_blank' => trans('messages.choose'),
                        'type' => 'radio',
                        'name' => 'lists_segments[' . $index . '][is_default]',
                        'label' => '',
                        'popup' => trans('messages.campaign_default_list_help'),
                        'value' => $lists_segment_group['is_default'],
                        'options' => [['text' => trans('messages.selected'), 'value' => 'true']],
                        'rules' => [],
                        'radio_group' => 'campaign_list_info_defaulf',
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-4 list_select_box"
            target-box="segments-select-box"
            segments-url="{{ action('SegmentController@selectBox') }}"
        >
            @include('helpers.form_control', [
                'name' => 'lists_segments[' . $index . '][mail_list_uid]',
                'class' => 'list-select',
                'include_blank' => trans('messages.choose'),
                'type' => 'select',
                'label' => trans('messages.which_list_send'),
                'value' => (is_object($lists_segment_group['list']) ? $lists_segment_group['list']->uid : ""),
                'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                'rules' => isset($rules) ? $rules : []
            ])
        </div>
        <div class="col-md-5 segments-select-box multiple">
            @if (is_object($lists_segment_group['list']) && collect($lists_segment_group['list']->readCache('SegmentSelectOptions', []))->count())
                @include('helpers.form_control', [
                    'value' => implode(",", $lists_segment_group['segment_uids']),
                    'type' => 'select',
                    'name' => 'lists_segments[' . $index . '][segment_uids][]',
                    'label' => trans('messages.which_segment_send'),
                    'options' => collect($lists_segment_group['list']->readCache('SegmentSelectOptions', [])),
                    'multiple' => true,
                    'quick_note' => trans('messages.leave_empty_to_choose_all_list')
                ])
            @endif
        </div>
        <div class="col-md-1 pt-28 del-col">
            <a onclick="$(this).parents('.condition-line').remove()" href="#delete" class="btn bg-danger-400"><span class="material-symbols-rounded">
delete_outline
</span></a>
        </div>
    </div>
    <hr class="mt-10">
</div>
