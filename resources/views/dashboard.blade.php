@extends('layouts.core.frontend')

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('content')
    <h2 class="mt-4 pt-2">{!! trans('messages.frontend_dashboard_hello', ['name' => Auth::user()->displayName()]) !!}</h2>
    <p>{!! trans('messages.frontend_dashboard_welcome') !!}</p>

    <h3 class="mt-5 mb-3">
        <span class="material-symbols-rounded me-2">
            donut_large
        </span>
        {{ trans("messages.used_quota") }}
    </h3>
    <p>{{ trans('messages.dashboard.credit.wording') }}</p>
    <div class="row quota_box">
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.sending_quota') }}</label>
                    <div class="pull-right text-semibold">
                        <span class="text-muted">{{ number_with_delimiter($sendingCreditsUsed) }}/{{ ($sendingCreditsLimit == -1) ? '∞' : number_with_delimiter($sendingCreditsLimit) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ number_to_percentage($sendingCreditsUsedPercentage) }}</span>
                    </div>
                </div>
                
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ ($sendingCreditsUsedPercentage >= 0.8) ? 'danger' : 'primary' }}" style="width: {{ $sendingCreditsUsedPercentage*100  }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.list') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->listsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxLists()) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ Auth::user()->customer->displayListsUsage() }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->listsUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->listsUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.campaign') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->campaignsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxCampaigns()) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ Auth::user()->customer->displayCampaignsUsage() }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->campaignsUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->campaignsUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.subscriber') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->readCache('SubscriberCount', 0)) }}/{{ number_with_delimiter(Auth::user()->customer->maxSubscribers()) }}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{ Auth::user()->customer->displaySubscribersUsage() }}</span>
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->subscribersUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->readCache('SubscriberUsage', 0) }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('_dashboard_campaigns')

    @include('_dashboard_list_growth')    


    @if (isSiteDemo())
    <h3 class="mt-5 mb-3"><span class="material-symbols-rounded me-2">
        star_half
        </span> {{ trans('messages.top_5') }}
    </h3>

    <ul class="nav nav-tabs nav-underline" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="campaign_opens-tab" data-bs-toggle="tab" data-bs-target="#campaign_opens" role="button" role="tab" aria-controls="campaign_opens" aria-selected="true">
                {{ trans('messages.campaign_opens') }}
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="campaign_clicks-tab" data-bs-toggle="tab" data-bs-target="#campaign_clicks" role="button" role="tab" aria-controls="campaign_clicks" aria-selected="false">
                {{ trans('messages.campaign_clicks') }}
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="clicked_links-tab" data-bs-toggle="tab" data-bs-target="#clicked_links" role="button" role="tab" aria-controls="clicked_links" aria-selected="false">
                {{ trans('messages.clicked_links') }}
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="campaign_opens" role="tabpanel" aria-labelledby="campaign_opens-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topOpens(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                                {{ $item->name }}
                                            </a>
                                        </h6>
                                        <p class="mb-0">
                                            {!! $item->displayRecipients() !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->aggregate) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.opens') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->readCache('UniqOpenCount')) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.uniq_opens') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ (null !== $item->lastOpen()) ? Auth::user()->customer->formatDateTime($item->lastOpen()->created_at, 'date_full') : "" }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_open') }}</span>
                            </div>
                        </div>

                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">
                                auto_awesome
                            </span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="tab-pane fade" id="campaign_clicks" role="tabpanel" aria-labelledby="campaign_clicks-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topClicks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                                {{ $item->name }}
                                            </a>
                                        </h6>
                                        <p>
                                            {!! $item->displayRecipients() !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->urlCount() }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.urls') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">
                                auto_awesome
                            </span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="tab-pane fade" id="clicked_links" role="tabpanel" aria-labelledby="clicked_links-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topLinks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="number d-inline-block me-3">{{ $num+1 }}</i>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold url-truncate">
                                            <a title="{{ $item->url }}" href="{{ $item->url }}" target="_blank">
                                                {{ $item->url }}
                                            </a>
                                        </h6>
                                        <p>
                                            {{ $item->aggregate }} {{ trans('messages.campaigns') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li pt-0">
                        <div class="empty-list mt-0">
                            <span class="material-symbols-rounded">
                                auto_awesome
                            </span>
                            <span class="line-1">
                                {{ trans('messages.empty_record_message') }}
                            </span>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
    @endif

    <h3 class="mt-5 mb-3"><span class="material-symbols-rounded me-2">
        web_stories
        </span>
         {{ trans('messages.activity_log') }}</h3>

    @if (Auth::user()->customer->logs()->count() == 0)
        <div class="empty-list">
            <span class="material-symbols-rounded">
                auto_awesome
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
                    @foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
                        <!-- Sales stats -->
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <a href="{{ action('AccountController@profile') }}"><img width="50px" class="rounded-circle shadow-sm" src="{{ $log->customer->user->getProfileImageUrl() }}" alt=""></a>
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

    <br>
    <br>
@endsection
