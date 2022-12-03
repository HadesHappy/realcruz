@if ($customers->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($customers as $key => $item)
            <tr>
                <td width="1%">
                    <img width="50" class="rounded-circle me-2" src="{{ $item->user->getProfileImageUrl() }}" alt="">
                </td>
                <td>
                    <h5 class="m-0 text-bold">
                        <a class="kq_search d-block" href="{{ action('Admin\CustomerController@edit', $item->uid) }}">{{ $item->user->displayName() }}</a>
                    </h5>
                    <span class="text-muted kq_search">{{ $item->user->email }}</span><br>
                    <span class="text-muted kq_search">{{ trans('messages.customer.bounce_feedback_rate') }} <span title="{{ trans('messages.customer.bounce_feedback_rate_desc') }}" class="xtooltip">{{ number_to_percentage($item->readCache('Bounce/FeedbackRate') ?? 0) }}</span></span>
                    <br />
                    <span class="text-muted2">{{ trans('messages.created_at') }}: {{ Auth::user()->admin->formatDateTime($item->created_at, 'date_full') }}</span>
                </td>
                <td>
                    @if ($item->currentPlanName())
                        <h5 class="no-margin stat-num">
                            <span><i class="material-symbols-rounded">
assignment_turned_in
</i> {{ $item->currentPlanName() }}</span>
                        </h5>
                        <span class="text-muted2">{{ trans('messages.current_plan') }}</span>
                    @else
                        <span class="text-muted2">{{ trans('messages.customer.no_active_subscription') }}</span>
                    @endif
                </td>
                <td class="stat-fix-size">
                    @if ($item->activeSubscription())
                        <div class="d-flex">
                            <div class="single-stat-box pull-left me-5">
                                <span class="no-margin text-primary stat-num">{{ number_with_delimiter($item->activeSubscription()->getCreditsUsedPercentageDuringPlanCycle('send')*100) }}%</span>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-info" style="width: {{ $item->activeSubscription()->getCreditsUsedPercentageDuringPlanCycle('send')*100 }}%">
                                    </div>
                                </div>
                                <span class="text-muted">
                                    <strong>{{ number_with_delimiter($item->activeSubscription()->getCreditsUsedDuringPlanCycle('send')) }}/{{ ($item->activeSubscription()->getCreditsLimit('send') == -1) ? '∞' : number_with_delimiter($item->activeSubscription()->getCreditsLimit('send')) }}</strong>
                                    <div class="text-nowrap">{{ trans('messages.sending_credits_used') }}</div>
                                </span>
                            </div>
                            <div class="single-stat-box pull-left">
                                <span class="no-margin text-primary stat-num">{{ $item->displaySubscribersUsage() }}</span>
                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-info" style="width: {{ $item->readCache('SubscriberUsage') }}%">
                                    </div>
                                </div>
                                <span class="text-muted"><strong>{{ number_with_delimiter($item->readCache('SubscriberCount')) }}/{{ number_with_delimiter($item->maxSubscribers()) }}</strong>
                                <br /> {{ trans('messages.subscribers') }}</span>
                            </div>
                        </div>
                    @endif
                </td>
                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $item->status }}">{{ trans('messages.user_status_' . $item->status) }}</span>
                    </span>
                </td>
                <td class="text-end">
                    @can('loginAs', $item)
                        <a href="{{ action('Admin\CustomerController@loginAs', $item->uid) }}" data-popup="tooltip"
                            title="{{ trans('messages.login_as_this_customer') }}" role="button"
                            class="btn btn-primary btn-icon"><span class="material-symbols-rounded">
login
</span></a>
                    @endcan
                    @can('update', $item)
                        <a href="{{ action('Admin\CustomerController@edit', $item->uid) }}"
                            data-popup="tooltip" title="{{ trans('messages.edit') }}"
                            role="button" class="btn btn-secondary btn-icon"><span class="material-symbols-rounded">
edit
</span></a>
                    @endcan
                    @if (Auth::user()->can('delete', $item) ||
                        Auth::user()->can('enable', $item) ||
                        Auth::user()->can('disable', $item) ||
                        Auth::user()->can('assignPlan', $item)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('assignPlan', $item)
                                    <li>
                                        <a
                                            href="{{ action('Admin\CustomerController@assignPlan', [
                                                "uid" => $item->uid,
                                            ]) }}"
                                            class="dropdown-item assign_plan_button"
                                        >
                                            <i class="material-symbols-rounded">
assignment_turned_in
</i>
                                             {{ trans('messages.customer.assign_plan') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('enable', $item)
                                    <li>
                                        <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.enable_customers_confirm') }}" href="{{ action('Admin\CustomerController@enable', ["uids" => $item->uid]) }}">
                                            <span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('disable', $item)
                                    <li>
                                        <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.disable_customers_confirm') }}" href="{{ action('Admin\CustomerController@disable', ["uids" => $item->uid]) }}">
                                            <span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('read', $item)
                                    <li>
                                        <a class="dropdown-item" href="{{ action('Admin\CustomerController@subscriptions', $item->uid) }}">
                                            <span class="material-symbols-rounded">
assignment_turned_in
</span> {{ trans('messages.subscriptions') }}
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_users_confirm') }}" href="{{ action('Admin\CustomerController@delete', ['uids' => $item->uid]) }}">
                                        <span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $customers])
    

    <script>
        var assignPlan;        
        $(function() {
            $('.assign_plan_button').click(function(e) {
                e.preventDefault();

                var src = $(this).attr('href');
                assignPlan = new Popup({
                    url: src
                });

                assignPlan.load();
            });
        });
    </script>

@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
people_outline
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
people_outline
</span>
        <span class="line-1">
            {{ trans('messages.customer_empty_line_1') }}
        </span>
    </div>
@endif
