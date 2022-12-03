@extends('layouts.popup.small')

@section('content')
    <div class="">
        <form id="conditionWaitCustom" action="{{ action('Automation2Controller@conditionWaitCustom') }}" method="POST">
            {{ csrf_field() }}
        
            <h4 class="text-semibold mt-0 mb-4">{{ trans('messages.condition.custom_wait') }}</h4>
            
            <div class="row boxing">
                <div class="col-md-4">
                    @include('helpers.form_control.number', [
                        'name' => 'wait_amount',
                        'value' => '1',
                        'label' => trans('messages.condition.wait_amount'),
                        'attributes' => [
                            'class' => 'numeric',
                            'min' => '1',
                        ],
                    ])
                </div>
                <div class="col-md-4">
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'name' => 'wait_unit',
                        'value' => 'day',
                        'label' => trans('messages.condition.wait_unit'),
                        'options' => [
                            ['text' => trans('messages.minute'), 'value' => 'minute'],
                            ['text' => trans('messages.hour'), 'value' => 'hour'],
                            ['text' => trans('messages.day'), 'value' => 'day'],
                        ],
                    ])
                </div>
            </div>
            <hr>
            <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
            <a href="javascript:;" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.close') }}</a>
        </form>
    </div>

    <script>
        $(function() {
            // 
            $("#conditionWaitCustom").submit(function( e ) {
                e.preventDefault();
                
                var url = $(this).attr('action');
                var data = $(this).serialize();
                var form = $(this);

                // copy
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false,
                    statusCode: {
                        // validate error
                        400: function (res) {
                            Automation2ConditionWait.customPopup.loadHtml(res.responseText);
                        }
                    },
                    success: function (response) {
                        Automation2ConditionWait.selectContainer.html(response);
                        Automation2ConditionWait.customPopup.hide();
                        initJs(Automation2ConditionWait.selectContainer);

                        Automation2ConditionWait.init();
                    }
                });
            });
        });
    </script>

@endsection
