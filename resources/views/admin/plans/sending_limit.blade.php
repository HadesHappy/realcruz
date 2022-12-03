@extends('layouts.popup.small')

@section('content')
    <div class="mc_section">
        <form id="sendingLimitForm" action="{{ action('Admin\PlanController@sendingLimit', ['uid' => $plan->uid]) }}" method="POST">
            {{ csrf_field() }}
            
            <input type="hidden" name="plan[options][sending_limit]" value="other" />
        
            <h2 class="text-semibold">{{ trans('messages.plan.sending_limit') }}</h2>
            
            <p>{!! trans('messages.plans.sending_limit.wording') !!}</p>
            
            <div class="row boxing">
                <div class="col-md-4">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => 'numeric',
                        'name' => 'plan[options][sending_quota]',
                        'value' => $plan->getOption('sending_quota'),
                        'label' => trans('messages.sending_quota'),
                        'help_class' => 'plan',
                        'rules' => $plan->rules()
                    ])
                </div>
                <div class="col-md-4">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => 'numeric',
                        'name' => 'plan[options][sending_quota_time]',
                        'value' => $plan->getOption('sending_quota_time'),
                        'label' => trans('messages.quota_time'),
                        'help_class' => 'plan',
                        'rules' => $plan->rules()
                    ])
                </div>
                <div class="col-md-4">
                    @include('helpers.form_control', ['type' => 'select',
                        'name' => 'plan[options][sending_quota_time_unit]',
                        'value' => $plan->getOption('sending_quota_time_unit'),
                        'label' => trans('messages.quota_time_unit'),
                        'options' => Acelle\Model\Plan::quotaTimeUnitOptions(),
                        'help_class' => 'plan',
                        'rules' => $plan->rules()
                    ])
                </div>
            </div>
            <hr>
            <button class="btn btn-secondary me-2">{{ trans('messages.sending_limit.save') }}</button>
            <a href="javascript:;" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.sending_limit.close') }}</a>
        </form>
    </div>

    <script>
        $(function() {
            // trigger modal form submit
            $("#sendingLimitForm").submit(function( event ) {
                event.preventDefault();

                $.ajax({
                    url: $(this).prop('action'),
                    type: $(this).prop('method'),
                    dataType: 'html',
                    data: $(this).serialize(),
                }).done(function(response) {
                    PlansSecurity.getBox().html(response);
                    
                    initJs(PlansSecurity.getBox());

                    PlansSecurity.getSendingLimitPopup().hide();
                });
            });
        });
    </script>

@endsection
