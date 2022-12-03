@extends('layouts.core.frontend')

@section('title', trans('messages.subscription'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.subscription') }}</li>
        </ul>
        <h1>
            <span class="text-semibold">{{ Auth::user()->displayName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("account._menu")

    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="notification_group">
                @if ($subscription->getItsOnlyUnpaidRenewInvoice())
                    @if (!\Auth::user()->customer->preferredPaymentGatewayCanAutoCharge())
                        @include('elements._notification', [
                            'level' => 'warning',
                            'message' => trans('messages.have_new_renew_invoice')
                        ])
                    @else
                        @include('elements._notification', [
                            'level' => 'warning',
                            'message' => trans('messages.have_new_renew_invoice.auto', [
                                'date' => Auth::user()->customer->formatDateTime($subscription->getDueDate(), 'date_full'),
                            ])
                        ])

                        @if ($subscription->getItsOnlyUnpaidRenewInvoice()->lastTransactionIsFailed())
                            @include('elements._notification', [
                                'level' => 'danger',
                                'message' => $subscription->getItsOnlyUnpaidRenewInvoice()->lastTransaction()->error
                            ])
                        @endif
                    @endif
                @endif

                @if ($subscription->getItsOnlyUnpaidChangePlanInvoice())
                    @include('elements._notification', [
                        'level' => 'warning',
                        'message' => trans('messages.have_new_change_plan_invoice', [
                            'link' => action('SubscriptionController@payment', [
                                'invoice_uid' => $subscription->getItsOnlyUnpaidChangePlanInvoice()->uid,
                            ]),
                        ])
                    ])
                @endif
            </div>

            <h2 class="text-semibold">{{ trans('messages.subscription') }}</h2>

            <div class="sub-section">
                @if ($subscription->isActive())
                    @if (!$subscription->cancelled())
                        {{-- @if (\Auth::user()->customer->supportsAutoBilling())
                            <p>
                                {!! trans('messages.subscription.current_subscription.recurring.wording', [
                                    'plan' => $plan->name,
                                    'money' => Acelle\Library\Tool::format_price($plan->price, $plan->currency->format),
                                    'remain' => $subscription->current_period_ends_at->diffForHumans(),
                                    'next_on' => Auth::user()->customer->formatDateTime($subscription->current_period_ends_at, 'date_full')
                                ]) !!}
                            </p>
                        @else --}}
                            <p>
                                {!! trans('messages.subscription.current_subscription.wording', [
                                    'plan' => $plan->name,
                                    'money' => Acelle\Library\Tool::format_price($plan->price, $plan->currency->format),
                                    'remain' => $subscription->current_period_ends_at->diffForHumans(),
                                    'next_on' => Auth::user()->customer->formatDateTime($subscription->current_period_ends_at, 'date_full')
                                ]) !!}
                            </p>
                        {{-- @endif --}}
                    @else
                        <p>
                            {!! trans('messages.subscription.current_subscription.cancel_at_end_of_period.wording', [
                                'plan' => $plan->name,
                                'money' => Acelle\Library\Tool::format_price($plan->price, $plan->currency->format),
                                'remain' => $subscription->current_period_ends_at->diffForHumans(),
                                'end_at' => Auth::user()->customer->formatDateTime($subscription->current_period_ends_at, 'date_full')
                            ]) !!}
                        </p>
                    @endif                        

                    @if (\Auth::user()->customer->can('cancel', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel.confirm') }}"
                            href="{{ action('SubscriptionController@cancel') }}"
                            class="btn btn-secondary me-1"
                        >
                            {{ trans('messages.subscription.cancel') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('resume', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.resume.confirm') }}"
                            href="{{ action('SubscriptionController@resume') }}"
                            class="btn btn-secondary me-2"
                        >
                            {{ trans('messages.subscription.resume') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('changePlan', $subscription))
                        <a
                            href="{{ action('SubscriptionController@changePlan', ["id" => $subscription->uid]) }}"
                            class="btn btn-default change_plan_button me-1"
                            data-size="sm"
                        >
                            {{ trans('messages.subscription.change_plan') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('cancelNow', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel_now.confirm') }}"
                            href="{{ action('SubscriptionController@cancelNow') }}"
                            class="btn btn-danger me-2"
                        >
                            {{ trans('messages.subscription.cancel_now') }}
                        </a>
                    @endif
                @endif
            </div>
            @include('subscription._invoices')

            <div class="sub-section">
                <div class="">
                    <div class="">
                        <h2 class="text-semibold">{{ trans('messages.plan_details') }} </h2>
                        <p>{{ trans('messages.plan_details.intro') }}</p>

                        @include('plans._details', ['plan' => $plan])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            @include('invoices.bill', [
                'bill' => $subscription->getUpcomingBillingInfo(),
                'payButton' => true,
            ])

            <div class="mt-4">
                @include('account._payment_info', [
                    'redirect' => action('SubscriptionController@index'),
                ])
            </div>
        </div>
    </div>
    

    <script>
        var changePlanModal;

        $(function() {
            $('.change_plan_button').click(function(e) {
                e.preventDefault();

                var src = $(this).attr('href');

                changePlanModal = new Popup();
                changePlanModal.load(src);
            });

            // Dotted list more/less
            $(document).on('click', '.dotted-list > li.more a', function() {
                var box = $(this).parents('.dotted-list');

                box.find('li').removeClass('hide');
                $(this).parents('li').hide();
            });
        });
    </script>

@endsection
