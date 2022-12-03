@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.plan.sending_limit') }}
@endsection

@section('content')
    <form id="billingCycleForm" action="{{ action('Admin\PlanController@billingCycle', ['uid' => $plan->uid]) }}" method="POST">
        {{ csrf_field() }}
        
        <input type="hidden" name="plan[options][billing_cycle]" value="other" />
        
        <h2 class="text-semibold">{{ trans('messages.plan.billing_cycle') }}</h2>
        
        <p>{!! trans('messages.plans.billing_cycle.wording') !!}</p>
            
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'class' => 'numeric',
                    'type' => 'text',
                    'name' => 'plan[general][frequency_amount]',
                    'value' => $plan->frequency_amount,
                    'help_class' => 'plan',
                    'rules' => $plan->generalRules(),
                ])
            </div>
            <div class="col-md-6">                        
                @include('helpers.form_control', ['type' => 'select',
                    'name' => 'plan[general][frequency_unit]',
                    'value' => $plan->frequency_unit,
                    'options' => $plan->timeUnitOptions(),
                    'help_class' => 'plan',
                ])
            </div>
        </div>
        <hr>
        <button id="billingCycleSubmitButton" class="btn btn-secondary me-1">{{ trans('messages.plans.billing_cycle.save') }}</button>
        <a href="javascript:;" onclick="PlansCustomBillingCycle.getPopup().hide();" class="btn btn-link">{{ trans('messages.plans.billing_cycle.close') }}</a>
    </form>

    <script>
        $(function() {
            $('#billingCycleForm').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                PlansCustomBillingCycle.getPopup().mask();
                addButtonMask($('#billingCycleSubmitButton'));

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    PlansCustomBillingCycle.getSelectContainer().html(response);

                    initJs(PlansCustomBillingCycle.getSelectContainer());

                    PlansCustomBillingCycle.getPopup().hide();

                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for debugging
                    PlansCustomBillingCycle.getPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    PlansCustomBillingCycle.getPopup().unmask();
                    removeButtonMask($('#billingCycleSubmitButton'));
                });
            });
        }); 
    </script>
@endsection

                    