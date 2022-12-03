@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.billing_information') }}
@endsection

@section('content')
    <form class="billing-address-form" action="{{ action('AccountController@editBillingAddress') }}"
        method="POST">
        {{ csrf_field() }}
        @if (request()->user()->customer->contact)
            @include('helpers.form_control', [
                'type' => 'checkbox2',
                'name' => 'same_as_contact',
                'value' => (request()->same_as_contact == 'true' ? 'true' : 'false'),
                'label' => trans('messages.same_as_contact'),
                'options' => ['false', 'true'],
                'help_class' => 'billing_address',
                'rules' => ['first_name' => 'required'],
            ])
        @endif

        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'first_name',
                    'value' => $billingAddress->first_name,
                    'label' => trans('messages.first_name'),
                    'help_class' => 'billing_address',
                    'rules' => ['first_name' => 'required'],
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'last_name',
                    'value' => $billingAddress->last_name,
                    'label' => trans('messages.last_name'),
                    'help_class' => 'billing_address',
                    'rules' => ['last_name' => 'required'],
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'email',
                    'value' => $billingAddress->email,
                    'label' => trans('messages.email_address'),
                    'help_class' => 'billing_address',
                    'rules' => ['email' => 'required'],
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'phone',
                    'value' => $billingAddress->phone,
                    'label' => trans('messages.phone'),
                    'help_class' => 'billing_address',
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'address',
                    'value' => $billingAddress->address,
                    'label' => trans('messages.address'),
                    'help_class' => 'billing_address',
                    'rules' => ['address' => 'required'],
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'country_id',
                    'value' => $billingAddress->country_id,
                    'label' => trans('messages.country'),
                    'options' => Acelle\Model\Country::getSelectOptions(),
                    'include_blank' => trans('messages.select_country'),
                    'help_class' => 'billing_address',
                    'rules' => ['country_id' => 'required'],
                    // 'readonly' => request()->same_as_contact == 'true',
                ])
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-secondary">{{ trans('messages.save') }}</button>
        </div>
    </form>

    <script>
        $('[name=same_as_contact]').change(function() {
            var checked = $(this).is(':checked');
            
            $.ajax({
                url: '{{ action('AccountController@editBillingAddress') }}',
                method: 'GET',
                data: {
                    same_as_contact: checked
                },
                success: function (response) {
                    billingPopup.loadHtml(response);
                }
            });
        });
        $('.billing-address-form').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            addMaskLoading();

            // 
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        billingPopup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (response) {
                    removeMaskLoading();

                    // notify
                    notify({
                        type: response.status,
                        title: '{{ trans('messages.notify.success') }}',
                        message: response.message
                    }); 

                    billingPopup.hide();

                    window.location.reload();
                }
            });
        })
            
    </script>
@endsection