@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.edit_payment_method') }}
@endsection

@section('content')
    <h4 class="mb-2 mt-0">{{ trans('messages.payment.choose_payment') }}</h4>
    <div class="mb-4">{!! trans('messages.payment.choose_payment.desc') !!}</div>
    <form class="edit-payment" action="{{ action('AccountController@editPaymentMethod') }}"
        method="POST">
        {{ csrf_field() }}

        <input type="hidden" name="return_url" value="{{ $redirect }}" />

        <div class="sub-section mb-30 choose-payment-methods">      
            @foreach(Acelle\Library\Facades\Billing::getEnabledPaymentGateways() as $gateway)
                <div class="choose-payment-method">
                    <div class="d-flex p-3 choose-payment choose-payment-{{ $gateway->getType() }}">
                        <div class="text-end pl-2 pr-2">
                            <div class="d-flex align-items-center form-group-mb-0 pt-1" style="width: 30px">
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => request()->user()->customer->getPreferredPaymentGateway() ? request()->user()->customer->getPreferredPaymentGateway()->getType() : '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => $gateway->getType(), 'text' => ''],
                                    ],
                                ])
                                <div class="check"></div>
                            </div>
                        </div>
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-1">{{ $gateway->getName() }}</h4>
                            <p class="mb-3">{{ trans('messages.'.$gateway->getType().'.user.description') }}</p>
                        </div>                        
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="sub-section">
            <div class="row">
                <div class="col-md-6">
                    <button link-method="POST"
                        class="btn btn-secondary px-4">
                            {{ trans('messages.save_payment_method') }}
                    </button>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>     
        
    </form>

    <script>
        $(function() {
            $('.edit-payment').on('submit', function(e) {
                if (!$('.choose-payment-methods>div [type=radio]:checked').length) {
                    e.preventDefault();

                    new Dialog('alert', {
                        message: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                        title: "{{ trans('messages.notify.error') }}"
                    });
                }
            });

            var manager = new GroupManager();

            $('.choose-payment-methods .choose-payment-method').each(function() {
                manager.add({
                    radio: $(this).find('input[name=payment_method]'),
                    box: $(this)
                });
            });

            manager.bind(function(group, others) {
                var doCheck = function() {
                    var checked = group.radio.is(':checked');
                    
                    if (checked) {
                        others.forEach(function(other) {
                            other.box.removeClass("current");
                        });
                        group.box.addClass("current");
                    } else {
                        group.box.removeClass("current");
                    }
                };

                group.radio.on('change', function() {
                    doCheck();
                });

                group.box.on('click', function() {
                    group.radio.prop('checked', true);

                    doCheck();
                });

                doCheck();
            });
        });
        
    </script>
@endsection