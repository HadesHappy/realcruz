@extends('layouts.popup.small')

@section('title')
    <i class="material-symbols-rounded alert-icon mr-2">addchart</i>
    {{ trans("messages.used_quota") }}
@endsection

@section('content')
    <!-- Alert if customer don't have any subscription -->
    @if (\Auth::user()->customer &&
        !\Auth::user()->customer->activeSubscription())
        <div class="alert alert-warning mt-4">
            <h4 class="ui-pnotify-title text-nowrap">
            {!! trans('messages.not_have_any_plan_notification', [
                'link' => action('SubscriptionController@index'),
            ]) !!}
            </h4>
            <div style="margin-top: 10px; clear: both; text-align: right; display: none;"></div>
        </div>
    @else
        @php
            $subscription = \Auth::user()->customer->activeSubscription();
        @endphp
        <div class="row quota_box mb-4">
            <div class="col-md-12">
                <div class="content-group-sm">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.sending_quota') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted">{{ number_with_delimiter($subscription->getCreditsUsedDuringPlanCycle('send')) }}/{{ ($subscription->getCreditsLimit('send') == -1) ? '∞' : number_with_delimiter($subscription->getCreditsLimit('send')) }}</span>
                            &nbsp;&nbsp;&nbsp;{{ number_to_percentage($subscription->getCreditsUsedPercentageDuringPlanCycle('send')) }}
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ $subscription->getCreditsUsedPercentageDuringPlanCycle('send') * 100 }}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-group-sm mt-4">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.list') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->listsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxLists()) }}</span>
                            &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayListsUsage() }}
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->listsUsage() }}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-group-sm mt-4">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.campaign') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted progress-xxs">{{ number_with_delimiter(Auth::user()->customer->campaignsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxCampaigns()) }}</span>
                            &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayCampaignsUsage() }}
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->campaignsUsage() }}%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-group-sm mt-4">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.subscriber') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->readCache('SubscriberCount')) }}/{{ number_with_delimiter(Auth::user()->customer->maxSubscribers()) }}</span>
                            &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySubscribersUsage() }}
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->readCache('SubscriberUsage') }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="content-group-sm mt-4">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.automation') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted progress-xxs">{{ number_with_delimiter(Auth::user()->customer->automationsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxAutomations()) }}</span>
                            &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayAutomationsUsage() }}
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->automationsUsage() }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="content-group-sm mt-4">
                    <div class="d-flex align-items-center">
                        <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.total_upload_size') }}</h5>
                        <div class="pull-right text-primary text-semibold">
                            <span class="text-muted progress-xxs">{{ number_with_delimiter(round(Auth::user()->customer->totalUploadSize(),2)) }}/{{ number_with_delimiter(Auth::user()->customer->maxTotalUploadSize()) }} (MB)</span>
                            &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->totalUploadSizeUsage() }}%
                        </div>
                    </div>
                    <div class="progress progress-xxs">
                        <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->totalUploadSizeUsage() }}%">
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->customer->useOwnSendingServer())
                <div class="col-md-12">
                    <div class="content-group-sm mt-4">
                        <div class="d-flex align-items-center">
                            <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.sending_server') }}</h5>
                            <div class="pull-right text-primary text-semibold">
                                <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->sendingServersCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxSendingServers()) }}</span>
                                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingServersUsage() }}
                            </div>
                        </div>
                        <div class="progress progress-xxs">
                            <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->sendingServersUsage() }}%">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->customer->can("create", new Acelle\Model\SendingDomain()))
                <div class="col-md-12">
                    <div class="content-group-sm mt-4">
                        <div class="d-flex align-items-center">
                            <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.sending_domain') }}</h5>
                            <div class="pull-right text-primary text-semibold">
                                <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->sendingDomainsCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxSendingDomains()) }}</span>
                                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingDomainsUsage() }}
                            </div>
                        </div>
                        <div class="progress progress-xxs">
                            <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->sendingDomainsUsage() }}%">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->customer->can("create", new Acelle\Model\EmailVerificationServer()))
                <div class="col-md-12">
                    <div class="content-group-sm mt-4">
                        <div class="d-flex align-items-center">
                            <h5 class="text-semibold mb-1 me-auto">{{ trans('messages.email_verification_server') }}</h5>
                            <div class="pull-right text-primary text-semibold">
                                <span class="text-muted">{{ number_with_delimiter(Auth::user()->customer->emailVerificationServersCount()) }}/{{ number_with_delimiter(Auth::user()->customer->maxEmailVerificationServers()) }}</span>
                                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayEmailVerificationServersUsage() }}
                            </div>
                        </div>
                        <div class="progress progress-xxs">
                            <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->emailVerificationServersUsage() }}%">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection