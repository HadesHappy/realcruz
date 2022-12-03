@extends('layouts.core.frontend_dark')

@section('title', trans('messages.subscriptions'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @if ($invoice->type !== \Acelle\Model\Invoice::TYPE_NEW_SUBSCRIPTION)
        <li class="nav-item d-flex align-items-center">
            <a  href="{{ action('SubscriptionController@index') }}"
                class="nav-link py-3 lvl-1">
                <i class="material-symbols-rounded me-2">arrow_back</i>
                <span>{{ trans('messages.go_back') }}</span>
            </a>
        </li>
    @endif

    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-8">
                @if (Auth::user()->customer->subscription && Auth::user()->customer->subscription->getUnpaidInvoice()  && Auth::user()->customer->subscription->getUnpaidInvoice()->lastTransactionIsFailed())
                    @include('elements._notification', [
                        'level' => 'danger',
                        'message' => Auth::user()->customer->subscription->getUnpaidInvoice()->lastTransaction()->error
                    ])
                @endif

                @include('subscription._selectPlan')

                @include('subscription._billingInformation')

                <div class="card mt-2 subscription-step">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">3</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.payment_method.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.payment_method.subtitle') }}</p>
                            </div>
                        </div>                        
                    </div>
                    <div class="card-body py-4" style="padding-left: 72px;padding-right:72px">
                        <form class="edit-payment"
                            action="{{ action('AccountController@editPaymentMethod') }}"
                            method="POST">
                            {{ csrf_field() }}
            
                            <p>{{ trans('messages.payment.choose_new_payment_method_to_proceed') }}</p>
            
                            <input type="hidden" name="return_url" value="{{ action('SubscriptionController@payment', [
                                'invoice_uid' => $invoice->uid,
                            ]) }}" />

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="sub-section mb-30 choose-payment-methods">      
                                        @foreach(Acelle\Library\Facades\Billing::getEnabledPaymentGateways() as $gateway)
                                            <div class="choose-payment-method">
                                                <div class="d-flex pt-3 pb-3 pl-2 choose-payment choose-payment-{{ $gateway->getType() }}">
                                                    <div class="text-end pe-2">
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
                                                        <h5 class="font-weight-semibold mb-1">{{ $gateway->getName() }}</h5>
                                                        <p class="mb-0">
                                                            @if (Lang::has('messages.'.$gateway->getType().'.user.description'))
                                                                {{ trans('messages.'.$gateway->getType().'.user.description') }}
                                                            @else
                                                                {{ $gateway->getDescription() }}
                                                            @endif
                                                        </p>
                                                    </div>    
                                                </div>           
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="order-box" style="position: sticky;top: 80px;">

                </div>
            </div>
        </div>
    </div>

    <script>
        var SubscriptionPayment = {
            orderBox: null,

            getOrderBox: function() {
                if (this.orderBox == null) {
                    this.orderBox = new Box($('.order-box'), '{{ action('SubscriptionController@orderBox') }}');
                }
                return this.orderBox;
            }
        }

        $(function() {
            // payment_method data
            if ($('.choose-payment-methods>div [type=radio]:checked').length) {
                SubscriptionPayment.getOrderBox().data = {
                    payment_method: $('.choose-payment-methods>div [type=radio]:checked').val()
                };
            }

            SubscriptionPayment.getOrderBox().load();

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

                        // set payment method
                        SubscriptionPayment.getOrderBox().data = {
                            payment_method: group.radio.val()
                        };
                        SubscriptionPayment.getOrderBox().load();
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

            // 
            $('.other-payment-click').on('click', function() {
                $('.edit-payment').show();
                $('.current_payment').hide();
                $(this).hide();
            });
        });
        
    </script>

    <script>
        $('.other-payment-click').on('click', function() {
            $('.edit-payment').show();
            $('.current_payment').hide();
            $(this).hide();
        });
    </script>

@endsection