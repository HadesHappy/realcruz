@extends('layouts.popup.full')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="sub-section">
                
                    <h2>{{ trans('messages.subscription.select_a_plan') }}</h2>
                    <p>{{ trans('messages.subscription.change_plan.select_below') }}</p>
                
                    @if (empty($plans))
                        <div class="row">
                            <div class="col-md-6">
                                @include('elements._notification', [
                                    'level' => 'danger',
                                    'message' => trans('messages.plan.no_available_plan')
                                ])
                            </div>
                        </div>
                    @else
                        <div class="new-price-box" style="margin-right: -30px">
                            <div class="">

                                @foreach ($plans as $key => $plan)
                                    <div
                                        data-url="{{ action('SubscriptionController@orderBox', [
                                            'plan_uid' => $plan->uid,
                                        ]) }}"
                                        class="new-price-item mb-3 d-inline-block plan-item showed
                                            {{ $subscription->plan->uid == $plan->uid ? 'disabled' : '' }}
                                        "
                                        style="width: calc(20% - 20px)">
                                        <div style="height: 100px">
                                            <div class="price">
                                                {!! format_price($plan->price, $plan->currency->format, true) !!}
                                                <span class="p-currency-code">{{ $plan->currency->code }}</span>
                                            </div>
                                            <p><span class="material-symbols-rounded text-muted2">
                                                restore
                                                </span> {{ $plan->displayFrequencyTime() }}</p>
                                        </div>
                                        <hr class="mb-2" style="width: 40px">
                                        <div style="height: 40px">
                                            <label class="plan-title fs-5 fw-600 mt-0">{{ $plan->name }}</label>
                                        </div>

                                        <div style="height: 130px">
                                            <p class="mt-4">{{ $plan->description }}</p>
                                        </div>

                                        <span class="time-box d-block text-center small py-2 fw-600">
                                            <div class="mb-1">
                                                <span>{{ $plan->displayTotalQuota() }} {{ trans('messages.sending_total_quota_label') }}</span>
                                            </div>
                                            <div>
                                                <span>{{ $plan->displayMaxSubscriber() }} {{ trans('messages.contacts') }}</span>
                                            </div>
                                        </span>

                                        @if ($subscription->plan->uid == $plan->uid)
                                            <a
                                                href="javascript:;"
                                                class="btn btn-secondary rounded-3 d-block mt-4 shadow-sm" disabled>
                                                    {{ trans('messages.plan.current_subscribed') }}
                                            </a>
                                        @else
                                            <a
                                                link-method="POST"
                                                href="{{ action('SubscriptionController@changePlan', ['plan_uid' => $plan->uid]) }}"
                                                class="btn btn-primary rounded-3 d-block mt-4 shadow-sm">
                                                    {{ trans('messages.plan.select') }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif
        
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>

    <script>
        $(function() {
            var manager = new GroupManager();
            $('.plan-item').each(function() {
                manager.add({
                    box: $(this),
                    url: $(this).attr('data-url')
                });
            });

            manager.bind(function(group, others) {
                group.box.on('click', function() {
                    group.box.addClass('current');

                    others.forEach(function(other) {
                        other.box.removeClass('current');
                    });
                })
            });
        });
    </script>
@endsection