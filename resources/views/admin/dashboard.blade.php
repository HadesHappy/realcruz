@extends('layouts.core.backend')

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('content')
    <h1 class="mb-2 mt-4">{{ trans('messages.backend_dashboard_hello', ['name' => Auth::user()->displayName()]) }}</h1>
    <p>{{ trans('messages.backend_dashboard_welcome') }}</p>

    @include('admin.notifications._top', ['notifications' => $notifications])

    <div class="row mt-5">
        <div class="col-md-6">
            <h4 class="text-semibold"><span class="material-symbols-rounded me-2">
people_outline
</span> {{ trans('messages.customers_growth') }}</h4>
            @include('admin.customers._growth_chart')
        </div>
        <div class="col-md-6">
            <h4 class="text-semibold"><i class="material-symbols-rounded me-2">
assignment_turned_in
</i> {{ trans('messages.plans_chart') }}</h4>
            @include('admin.plans._pie_chart')
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h4 class="text-semibold">
                <span class="material-symbols-rounded me-2">
assignment_turned_in
</span>
                {{ trans('messages.recent_subscriptions') }}
            </h4>
            <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_subscriptions.wording', [ 'here' => action('Admin\SubscriptionController@index') ]) !!}</p>
            <ul class="modern-listing mt-0 mb-0 top-border-none type2">
                @forelse (Auth::user()->admin->recentSubscriptions() as $subscription)
                    <li class="">
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex">
                                    <div class="me-3 pt-1">
                                        <svg class="svg-fill-current-all" style="width: 30px;height:30px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 126.3 124.4" style="enable-background:new 0 0 126.3 124.4;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M26.4,113.3c-7,0-13.6-2.7-18.6-7.7C2.8,100.7,0,94.1,0,87c0,0,0-0.1,0-0.1V32C0.1,17.4,12,5.6,26.5,5.6h36.3 c1.9,0,3.5,1.6,3.5,3.5s-1.6,3.5-3.5,3.5H26.5C15.8,12.6,7,21.3,7,32v54.9c0,5.3,2.1,10.1,5.7,13.8s8.6,5.7,13.7,5.6h50.1 c10.7,0,19.4-8.7,19.4-19.4V61c0-1.9,1.6-3.5,3.5-3.5s3.5,1.6,3.5,3.5v25.9c0,14.6-11.8,26.4-26.4,26.4H26.5 C26.5,113.3,26.4,113.3,26.4,113.3z"/><path class="st0" d="M51.5,60.9c-9.3,0-16.8-7.5-16.8-16.8s7.5-16.8,16.8-16.8s16.8,7.5,16.8,16.8S60.8,60.9,51.5,60.9z M51.5,34.3c-5.4,0-9.8,4.4-9.8,9.8s4.4,9.8,9.8,9.8s9.8-4.4,9.8-9.8S56.9,34.3,51.5,34.3z"/><path class="st0" d="M77.9,77.3H25.1c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h52.8c1.9,0,3.5,1.6,3.5,3.5S79.8,77.3,77.9,77.3z"/><path class="st0" d="M77.9,91.6H25.1c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h52.8c1.9,0,3.5,1.6,3.5,3.5S79.8,91.6,77.9,91.6z"/><path class="st0" d="M82,54.9c-0.7,0-1.4-0.2-2.1-0.7c-1.1-0.8-1.6-2.1-1.4-3.4l2.7-15.6L70,24.3c-1-0.9-1.3-2.3-0.9-3.6 c0.4-1.3,1.5-2.2,2.8-2.4l15.6-2.2l7-14.1c0.6-1.2,1.8-2,3.1-2s2.5,0.8,3.1,2l7,14.1l15.6,2.2c1.3,0.2,2.4,1.1,2.8,2.4 c0.4,1.3,0.1,2.7-0.9,3.6L114,35.2l2.7,15.6c0.2,1.3-0.3,2.6-1.4,3.4c-1.1,0.8-2.5,0.9-3.7,0.3l-14-7.3l-14,7.3 C83.1,54.8,82.6,54.9,82,54.9z M97.6,39.7c0.6,0,1.1,0.1,1.6,0.4l9.3,4.9l-1.8-10.4c-0.2-1.1,0.2-2.3,1-3.1l7.5-7.2l-10.3-1.5 c-1.1-0.2-2.1-0.9-2.6-1.9l-4.7-9.4l-4.7,9.4c-0.5,1-1.5,1.8-2.6,1.9L80,24.2l7.5,7.2c0.8,0.8,1.2,2,1,3.1L86.7,45l9.3-4.9 C96.5,39.8,97,39.7,97.6,39.7z"/><path class="st0" d="M86.5,124.4H80c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h6.5c22.7,0,23.9-20,23.9-24V76.2 c0-1.9,1.6-3.5,3.5-3.5s3.5,1.6,3.5,3.5v17.2C117.4,108.8,107.8,124.4,86.5,124.4z"/></g></g></svg>
                                    </div>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('Admin\CustomerController@subscriptions', $subscription->customer->uid) }}">
                                                {{ $subscription->plan->name }}
                                            </a>
                                        </h6>
                                        <p class="mb-0">
                                            <i class="material-symbols-rounded me-1">
        person_outline
        </i>
                                            {{ $subscription->customer->user->displayName() }}
                                        </p>
                                    </div>
                                </div>
                                    
                                    
                            </div>
                            <div class="col-sm-4 col-md-4 text-left">
                                @if ($subscription->isEnded())
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Auth::user()->admin->formatDateTime($subscription->ends_at, 'date_full') }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.ended_on') }}</span>
                                @elseif ($subscription->isActive())
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Auth::user()->admin->formatDateTime($subscription->ends_at, 'date_full') }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.ends_on') }}</span>
                                @else
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Auth::user()->admin->formatDateTime($subscription->updated_at, 'date_full') }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.updated_at') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-3 col-md-3 text-left">
                                <span class="text-muted2 list-status pull-left">
                                    <span class="label label-flat bg-{{ $subscription->status }}">{{ trans('messages.subscription.status.' . $subscription->status) }}</span>
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li">
                        {{ trans('messages.empty_record_message') }}
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="col-md-6">
            <h4 class="text-semibold">
                <span class="material-symbols-rounded me-2">
people_outline
</span>
                {{ trans('messages.recent_customers') }}
            </h4>
            <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_customers.wording', [ 'here' => action('Admin\CustomerController@index') ]) !!}</p>
            <ul class="modern-listing mt-0 mb-0 top-border-none type2">
                @forelse(Auth::user()->admin->recentCustomers() as $customer)
                    <li class="">
                        <div class="row">
                            <div class="col-sm-8 col-md-8">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <img width="40" class="rounded-circle shadow-sm me-2 pull-left" src="{{ $customer->user->getProfileImageUrl() }}" alt="">
                                    </div>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('Admin\CustomerController@edit', $customer->uid) }}">
                                                {{ $customer->user->displayName() }}
                                            </a>
                                        </h6>
                                        <p class="mb-0 admin-line admin-recent-sencond-line" title="{{ $customer->user->email }}">
                                            {{ $customer->user->email }}
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-sm-4 col-md-4 text-end">
                                <h6 class="no-margin text-semibold small">
                                    {{ Auth::user()->admin->formatDateTime($customer->created_at, 'date_full') }}
                                </h6>
                                <span class="">{{ trans('messages.created_at') }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li">
                        {{ trans('messages.empty_record_message') }}
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <h4 class="text-semibold mt-5">
        <span class="material-symbols-rounded me-2">
restore
</span>
        {{ trans('messages.activities') }}
    </h4>
    <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_activity.wording', [ 'here' => action('Admin\CustomerController@index') ]) !!}</p>
    @if (\Auth::user()->admin->getLogs()->count() == 0)
        <div class="empty-list">
            <span class="material-symbols-rounded me-2">
history_toggle_off
</span>
            <span class="line-1">
                {{ trans('messages.no_activity_logs') }}
            </span>
        </div>
    @else
        <div class="action-log-box">
            <!-- Timeline -->
            <div class="">
                <div class="mt-4">
                    @foreach (\Auth::user()->admin->getLogs()->take(20)->get() as $log)
                        <!-- Sales stats -->
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <a href="#"><img width="50px" class="rounded-circle shadow-sm" src="{{ $log->customer->user->getProfileImageUrl() }}" alt=""></a>
                            </div>

                            <div class="card px-0 shadow-sm container-fluid">
                                <div class="card-body pt-2">
                                    <div class="d-flex align-items-center pt-1">
                                        <label class="panel-title text-semibold my-0 fw-600">{{ $log->customer->user->displayName() }}</label>
                                        <div class="d-flex align-items-center ms-auto text-muted">
                                            <span style="font-size: 18px" class="material-symbols-rounded ms-auto me-2">
                                                history
                                            </span>
                                            <div class="">
                                                <span class="heading-text"><i class="icon-history position-left text-success"></i> {{ $log->created_at->timezone($currentTimezone)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0">{!! $log->message() !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /sales stats -->
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="sub-section mb-4 mt-5" style="margin-top: 60px">
        <h4 class="text-semibold mt-5"><span class="material-symbols-rounded me-2">
            running_with_errors
            </span> {{ trans('messages.resources_statistics') }}</h4>
        <p>{{ trans('messages.resources_statistics_intro') }}</p>
        <div class="row">
            <div class="col-md-6">
                <ul class="dotted-list topborder section">
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
people_outline
</span> {{ trans('messages.customers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllCustomers()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
assignment_turned_in
</span> {{ trans('messages.subscriptions') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllSubscriptions()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><i class="material-symbols-rounded me-2">
assignment_turned_in
</i> {{ trans('messages.plans') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllPlans()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
contact_mail
</span> {{ trans('messages.lists') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllLists()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
people_outline
</span> {{ trans('messages.subscribers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ number_with_delimiter($subscribersCount) }}</mc:flag>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="dotted-list topborder section">
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
manage_accounts
</span> {{ trans('messages.admins') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllAdmins()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
people
</span> {{ trans('messages.admin_groups') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllAdminGroups()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
dns
</span> {{ trans('messages.sending_servers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllSendingServers()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
public
</span> {{ trans('messages.sending_domains') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ $sendingDomainsCount }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
forward_to_inbox
</span> {{ trans('messages.campaigns') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllCampaigns()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-symbols-rounded me-2">
schedule
</span> {{ trans('messages.automations') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ number_with_delimiter($automationsCount) }}</mc:flag>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
