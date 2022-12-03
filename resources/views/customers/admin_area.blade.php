@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.customer.admin_area') }}
@endsection

@section('content')

    <p class="alert alert-info">{!! trans('messages.current_login_as', ["name" => Auth::user()->displayName()]) !!}</p>

    <h5 class="mt-0 mb-10"><span class="material-symbols-rounded">
auto_awesome_mosaic
</span> {{ trans('messages.subscription_of', ['name' => Auth::user()->displayName()]) }}</h5>
    
    @if (isset($subscription))
        <ul class="dotted-list topborder section">
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.plan_name') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag><strong>{{ $subscription->plan->name }}</strong></mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.status') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>
                        @switch($subscription->status)
                            @case(Acelle\Model\Subscription::STATUS_ACTIVE)
                                <span style="cursor:pointer"
                                    class=" label bg-success"
                                >
                                    {{ trans('messages.subscription.status.active') }}
                                </span>

                                @break
                            @case(Acelle\Model\Subscription::STATUS_NEW)
                                @if ($subscription->getUnpaidInvoice())
                                    <span style="cursor:pointer"
                                        class="label bg-m-warning"
                                    >
                                        {{ trans('messages.subscription.status.wait_for_payment') }}
                                    </span>	
                                @endif
                                
                                @break
                            @default
                                <span style="cursor:pointer"
                                    class="label bg-{{ $subscription->status }}"
                                >
                                    {{ trans('messages.subscription.status.' . $subscription->status) }}
                                </span>
                        @endswitch
                    </mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.subscription.start_from') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>{{ $subscription->created_at->diffForHumans() }}</mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.subscription.next_billing_date') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>{{ Auth::user()->customer->formatDateTime($next_billing_date, 'date_full') }}</mc:flag>
                </div>
            </li>
        </ul>
    @else
        <div class="alert alert-warning mb-0">
            {{ trans('messages.customer_has_no_plan') }}
        </div>
    @endif
    <div class="text-left mt-5">
        <a href="{{ action("CustomerController@loginBack") }}" class="btn btn-secondary">{{ trans('messages.customer.back_to_admin') }}</a>
        <button role="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.close') }}</button>
    </div>
@endsection
