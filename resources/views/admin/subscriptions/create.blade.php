@extends('layouts.popup.medium')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mt-0">{{ trans('messages.subscription.create_new_subsctiption') }}</h2>
            <p>{{ trans('messages.subscription.create_new_subsctiption.intro') }}</p>   

            <form enctype="multipart/form-data" action="{{ action('Admin\SubscriptionController@create') }}" method="POST" class="subscription-form form-validate-jqueryx">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        @include('helpers.form_control', [
                            'type' => 'select_ajax',
                            'class' => '',
                            'name' => 'customer_uid',
                            'label' => trans('messages.select_customer'),
                            'help_class' => 'subscription',
                            'rules' => $rules,
                            'url' => action('Admin\CustomerController@select2'),
                            'placeholder' => trans('messages.select_customer')
                        ])

                        @include('helpers.form_control', [
                            'type' => 'select_ajax',
                            'class' => '',
                            'name' => 'plan_uid',
                            'label' => trans('messages.select_plan'),
                            'help_class' => 'subscription',
                            'rules' => $rules,
                            'url' => action('Admin\PlanController@select2'),
                            'placeholder' => trans('messages.select_plan')
                        ])
                    </div>
                </div>

                <button class="btn btn-primary bg-grey mt-3">{{ trans('messages.subscription.create') }}</button>
            </form>
        </div>
    </div>

    <script>
        $('.subscription-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                statusCode: {
                    // validate error
                    400: function (res) {
                        newSubscription.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    newSubscription.hide();

                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                }
            });
        });
    </script>
@endsection