@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form action="{{ action("SubscriberController@assignValues", $list->uid) }}"
                method="POST" class="assign-values"
            >
                {{ csrf_field() }}

                @foreach (request()->uids as $uid)
                    <input type="hidden" name="uids[]" value="{{ $uid }}" />
                @endforeach

                <input type="hidden" name="select_tool" value="{{ request()->select_tool }}" />

                <h3 class="mb-3">{{ trans('messages.subscriber.assign_values') }}</h3>
                <p>{!! trans('messages.subscriber.assign_to_subscriber', [
                    'count' => number_with_delimiter($subscribers->count(), $precision = 0),
                ]) !!}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'select',
                    'class' => '',
                    'label' => '',
                    'name' => 'field_uid',
                    'value' => [],
                    'options' => $list->getFieldSelectOptions(),
                    'rules' => ['field_uid' => 'required'],
                    'placeholder' => trans('messages.subscriber.choose_a_field'),
                ])

                <div>
                    <label class="d-flex mt-4 pt-2">
                        <div class="checkmark-container">
                            <input type="radio" name="assign_type" value="single"
                                checked
                                class="has-checkmark radio-md mt-0" />
                            <span class="checkmark"></span>
                        </div>
                        <div class="pl-3">
                            {{ trans('messages.subscriber.a_fixed_value_for_contacts') }}
                        </div>
                    </label>
                    <label class="d-flex mt-3">
                        <div class="checkmark-container">
                            <input type="radio" name="assign_type" value="list"
                                {{ request()->assign_type == 'list' ? 'checked' : '' }}
                                class="has-checkmark radio-md mt-0" />
                            <span class="checkmark"></span>
                        </div>
                        <div class="pl-3">
                            {{ trans('messages.subscriber.unique_values_for_all_the_list') }}
                        </div>
                    </label>
                </div>

                <div class="assign-type mt-4" data-value="single" style="display:none">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => '',
                        'label' => trans('messages.subscriber.assign_values.value'),
                        'name' => 'single_value',
                        'options' => $list->getFieldSelectOptions(),
                        'rules' => ['value' => 'required'],
                        'placeholder' => trans('messages.subscriber.enter_field_value'),
                    ])
                </div>

                <div class="assign-type mt-4" data-value="list" style="display:none">
                    @include('helpers.form_control', [
                        'type' => 'textarea',
                        'class' => '',
                        'label' => trans('messages.subscriber.assign_values.value'),
                        'name' => 'list_value',
                        'options' => $list->getFieldSelectOptions(),
                        'rules' => ['value' => 'required'],
                        'placeholder' => trans('messages.subscriber.enter_field_value'),
                    ])
                </div>

                <div class="mt-4 pt-3">
                    <button class="btn btn-secondary">{{ trans('messages.automation.profile.tag') }}</button>
                </div>
        </div>
    </div>
    
    <script>
        $('form.assign-values').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var data = form.serialize();
            var url = form.attr('action');
            
            addMaskLoading('{{ trans('messages.subscriber.assign_values') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        assignValues.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // hide popup
                    assignValues.hide();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();
                }
            });    
        });

        function showType() {
            var box = $('.assign-values');
            var type = $('[name=assign_type]:checked').val();

            // content
            box.find('.assign-type').hide();
            box.find('.assign-type[data-value='+type+']').show();
        }

        $('[name=assign_type]').change(function(e) {
            showType();
        });

        showType();
    </script>
@endsection
