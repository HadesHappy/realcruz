<div class="modal-content">
    
        {{ csrf_field() }}
        <div class="modal-header bg-grey">
            <h5 class="modal-title">{{ trans('messages.subscription') }}</h5>
            <button role="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-semibold">{{ trans('messages.subscription.change_plan') }}</h2>
            
                    <div class="mb-20">
                        <div class="row">
                            <div class="col-md-12">                                
                                <p>{!! trans('messages.subscription.change_plan.wording') !!}</p>
                                    
                                <form enctype="multipart/form-data" action="{{ action('Admin\SubscriptionController@changePlan', $subscription->uid) }}" method="POST" class="form-validate-jquery">
                                    {{ csrf_field() }}
                
                                    @include('helpers.form_control', [
                                        'type' => 'select_ajax',
                                        'class' => 'subsciption-plan-select hook required',
                                        'name' => 'plan_uid',
                                        'label' => '',
                                        'help_class' => 'subscription',
                                        'url' => action('PlanController@select2', ['except' => $subscription->plan_id]),
                                        'placeholder' => trans('messages.select_plan')
                                    ])                            
                                
                                    
                                    <button type="submit" class="btn btn-primary bg-grey-600">
                                        {{ trans('messages.subscription.change_plan') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>