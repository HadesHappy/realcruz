@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="mc_section">
                <form class="custom-wait-time-form" action="{{ action('Automation2Controller@waitTime', $automation->uid) }}" method="POST">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="plan[options][billing_cycle]" value="other" />
                    
                    <h2 class="text-semibold">{{ trans('messages.automation.wait') }}</h2>
                    
                    <p>{!! trans('messages.automation.wait.wording') !!}</p>
                        
                    <div class="row">
                        <div class="col-md-6">
                            @include('helpers.form_control', [
                                'class' => 'numeric',
                                'type' => 'number',
                                'name' => 'amount',
                                'value' => '',
                                'help_class' => 'plan',
                                'rules' => ['amount' => 'required'],
                                'required' => true,
                            ])
                        </div>
                        <div class="col-md-6">                        
                            @include('helpers.form_control', ['type' => 'select',
                                'name' => 'unit',
                                'value' => '',
                                'options' => Acelle\Model\Automation2::waitTimeUnitOptions(),
                                'help_class' => 'plan',
                            ])
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                    <a href="javascript:;" onclick="waitTimePopup.hide()" class="btn btn-link me-2">{{ trans('messages.close') }}</a>
                </form>
            </div>
        </div>
    </div>
        
    <script>
        $('.custom-wait-time-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                statusCode: {
                    // validate error
                    400: function (res) {
                        sidebar.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    $('.custom-wait-time').html(`<input type="hidden" name="time" value="`+response.amount+` `+response.unit+`" />`);
                    waitTimePopup.hide();
                    
                    setTimeout(function() {
                        if($('.select-action-confirm').is(':visible')) {
                            $('.select-action-confirm').click();
                        } else {
                            $('.action-save-change').click();
                        }
                    },100);
                }
            });
        });
    </script>
@endsection