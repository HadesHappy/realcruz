@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.confirm'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded me-2">
forward_to_inbox
</span> {{ $campaign->name }}</span>
        </h1>

        @include('campaigns._steps', ['current' => 5])
    </div>

@endsection

@section('content')

    <div class="confirm-campaign-box">
        <form action="{{ action('CampaignController@confirm', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
            {{ csrf_field() }}

            <div class="head">
                <h2 class="text-semibold mb-2">{{ trans('messages.you_are_all_send') }}</h2>
                <p>{{ trans('messages.review_campaign_feeback') }}</p>
            </div>

            <ul class="modern-listing">
                @if (!is_null($score))

                    <li class="d-flex align-items-center">
                        <!-- {{ $count = $campaign->subscribersCount() }} -->

                        <span class="fs-4 me-4">
                            @if ($score['result'] == true)
                                <i class="material-symbols-rounded text-success">
                                    task_alt
                                </i>
                            @else
                                @if (!Acelle\Model\Setting::isYes('spamassassin.required'))
                                    <span class="material-symbols-rounded text-warning">
                                        error_outline
                                    </span>
                                @else
                                    <span class="material-symbols-rounded text-danger">
                                        highlight_off
                                    </span>
                                @endif
                            @endif
                           
                        </span>
                        <div class="me-auto">
                            <h4>{{ trans('messages.campaign.spam_score') }}</h4>
                            <p>
                                @if ($score['result'] == true)
                                    <i class="label label-flat bg-active">{{ trans('messages.campaign.score.passed') }} {{ $score['score'] }}</i>
                                @else
                                    <i class="badge badge-warning">{{ trans('messages.campaign.score.failed') }} {{ $score['score'] }}</i>
                                @endif
                            </p> 
                        </div>

                        <a href="{{ action('CampaignController@recipients', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                    </li>
                @endif
                <li class="d-flex align-items-center">
                    
                    <!-- {{ $count = $campaign->subscribersCount() }} -->

                    <span class="fs-4 me-4">
                        @if ($count)
                            <i class="material-symbols-rounded text-success">
    task_alt
                            </i>
                        @else
                            <span class="material-symbols-rounded text-danger">
                                highlight_off
                            </span>
                        @endif
                    </span>
                    <div class="me-auto">
                        <h4>{{ number_with_delimiter($count) }} {{ trans('messages.recipients') }}</h4>
                        <p>
                            {!! $campaign->displayRecipients() !!}
                        </p>
                    </div>

                    <a href="{{ action('CampaignController@recipients', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                </li>

                <li class="d-flex align-items-center">
                    <span class="fs-4 me-4">
                        <i class="material-symbols-rounded text-success">
                            task_alt
                        </i>
                    </span>
                    <div class="me-auto">
                        <h4>{{ trans('messages.email_subject') }}</h4>
                        <p>
                            {{ $campaign->subject }}
                        </p>    
                    </div>

                    <a href="{{ action('CampaignController@recipients', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                </li>

                <li class="d-flex align-items-center">
                    <span class="fs-4 me-4">
                        <i class="material-symbols-rounded text-success">
                            task_alt
                        </i>
                    </span>
                    <div class="me-auto">
                        <h4>{{ trans('messages.reply_to') }}</h4>
                        <p>
                            {{ $campaign->reply_to }}
                        </p>   
                    </div>

                    <a href="{{ action('CampaignController@setup', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                </li>

                <li class="d-flex align-items-center">
                    <span class="fs-4 me-4">
                        <i class="material-symbols-rounded text-success">
                            task_alt
                        </i>
                    </span>
                    <div class="me-auto">
                        <h4>{{ trans('messages.tracking') }}</h4>
                        <p>
                            @if ($campaign->track_open)
                                {{ trans('messages.opens') }}<pp>,</pp>
                            @endif
                            @if ($campaign->track_click)
                                {{ trans('messages.clicks') }}<pp>,</pp>
                            @endif
                        </p>
                    </div>

                    <a href="{{ action('CampaignController@setup', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                </li>

                <li class="d-flex align-items-center confirm-webhooks-summary">
                    <span class="fs-4 me-4">
                        <i class="material-symbols-rounded text-success">
                            task_alt
                        </i>
                    </span>
                    <div class="me-auto">
                        <h4>{{ number_with_delimiter($campaign->campaignWebhooks()->count()) }} {{ trans('messages.webhooks') }}</h4>
                        
                        <p class="">{{ trans('messages.webhooks.summary_desc') }}</p>
                        <p class="mb-0">{!! implode(' ', array_unique($campaign->campaignWebhooks()->select('type')->get()->map(function($h) {
                                return '<span class="text-muted2 small badge badge-success mt-2">' . trans('messages.webhook.type.' . $h->type . '.short') . '</span>';
                            })->toArray())) !!}
                        </p>
                    </div>

                    <a href="javascript:;" class="btn btn-secondary manage_webhooks_but">{{ trans('messages.webhooks.manage') }}</a>

                    <script>
                        $(function() {
                            // manage webhooks button click
                            $('.manage_webhooks_but').on('click', function(e) {
                                e.preventDefault();

                                CampaignsConfirm.getWebhooksPopup().load();
                            });
                        });
                    </script>
                </li>

                @if (!$campaign->run_at)
                    <li class="d-flex align-items-center">
                        <span class="fs-4 me-4">
                            <i class="material-symbols-rounded text-success">
                                task_alt
                            </i>
                        </span>
                        <div class="me-auto">
                            <h4>{{ trans('messages.campaign.run_immediately') }}</h4>
                            <p>
                                {{ trans('messages.campaign.click_send_to_proceed') }}
                            </p>
                        </div>

                        <a href="{{ action('CampaignController@setup', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                    </li>
                @else
                    <li class="d-flex align-items-center">
                        <span class="fs-4 me-4">
                            <i class="material-symbols-rounded text-muted2">
                                alarm
                            </i>
                        </span>
                        <div class="me-auto">
                            <h4>{{ trans('messages.campaign.scheduled_at') }} ({{ $campaign->run_at->diffForHumans() }})</h4>
                            <p>
                                {{ isset($campaign->run_at) ? Auth::user()->customer->formatDateTime($campaign->run_at, 'date_full') : "" }}
                            </p>
                        </div>

                        <a href="{{ action('CampaignController@setup', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                    </li>
                @endif
            </ul>

            @if ($campaign->step() >= 5)
                @if (!is_null($score) && !$score['result'])
                    @if (!Acelle\Model\Setting::isYes('spamassassin.required'))
                        <!--1.	Show ra error như dưới nhưng vẫn cho gửi campaign
                        2.	Khi user click Send thì show 1 cái confirm box: “Your message does not pass SpamScore test, are you sure you want to proceed anyway?”-->

                        <br />
                        <div class="text-end">
                            <span
                                onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                                href="#preview" class="btn btn-secondary me-1" data-uid="{{ $campaign->uid }}">
                                {{ trans('messages.preview') }} <span class="material-symbols-rounded">
visibility
</span>
                            </span>
                            <button class="btn btn-secondary me-1 send-a-test-email-link" data-uid="{{ $campaign->uid }}">{{ trans('messages.send_a_test_email') }} <i class="icon-envelop3 ml-5"></i> </button>
                            @if (!is_null($score))
                                <span onclick="$('#spam_score_confirm_model').modal('show')" class="btn btn-primary">{{ trans('messages.send') }} <i class="icon-paperplane ml-5"></i> </span>
                                <button style="display: none" class="btn btn-primary send-campaign-button">{{ trans('messages.send') }} <i class="icon-paperplane ml-5"></i> </button>
                            @else
                                <button class="btn btn-primary send-campaign-button">{{ trans('messages.send') }} <i class="icon-paperplane ml-5"></i> </button>
                            @endif
                        </div>

                        <!-- Basic modal -->
                        <div id="spam_score_confirm_model" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form class="confirm-link-form" onkeypress="return event.keyCode != 13;">
                                        <div class="modal-header">
                                            <button role="button" class="close" data-dismiss="modal">&times;</button>
                                            <h3 class="modal-title"><strong>{{ trans('messages.are_you_sure') }}</strong></h3>
                                        </div>

                                        <div class="modal-body">
                                            <p>{{ trans('messages.campaign.send_without_score_passed.warning') }}</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button role="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.cancel') }}</button>
                                            <a class="btn btn-secondary link-confirm-button" onclick="$('.send-campaign-button').click()">{{ trans('messages.confirm') }}</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /basic modal -->
                    @endif
                @else
                    <br />
                    <div class="text-end">
                        <button
                            type="button"
                            href="#preview" class="btn btn-default me-1 preview-as-button"
                        >
                            {{ trans('messages.campaign.preview_as') }} <span class="material-symbols-rounded ms-1">
                                contact_mail
</span>
                        </button>
                        <span
                            onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                            href="#preview" class="btn btn-secondary me-1" data-uid="{{ $campaign->uid }}">
                            {{ trans('messages.preview') }} <span class="material-symbols-rounded">
visibility
</span>
                        </span>
                        <button class="btn btn-secondary me-1 send-a-test-email-link" data-uid="{{ $campaign->uid }}">{{ trans('messages.send_a_test_email') }} <i class="icon-envelop3 ml-5"></i> </button>
                        <button class="btn btn-primary">{{ trans('messages.send') }} <i class="icon-paperplane ml-5"></i> </button>
                    </div>
                @endif


            @endif
        </form>
        <br><br><br>
    </div>

    <script>
        var CampaignsConfirm = {
			webhooksPopup: null,
			getWebhooksPopup: function() {
				if (this.webhooksPopup == null) {
					this.webhooksPopup = new Popup({
						url: '{{ action('CampaignController@webhooks', [
							'uid' => $campaign->uid,
						]) }}',
                        onclose: function() {
                            CampaignsConfirm.refresh();
                        }
					});
				}

				return this.webhooksPopup;
			},

            refresh: function() {
                // var box = confirm-webhooks-summary
                $.ajax({
                    url: "",
                    method: 'GET',
                    data: {
                        _token: CSRF_TOKEN
                    },
                    success: function (response) {
                        var html = $('<div>').html(response).find('.confirm-webhooks-summary').html();

                        $('.confirm-webhooks-summary').html(html);
                    }
                });
            }
		}

        $(document).ready(function() {
            // send a test email
            $('.send-a-test-email-link').on('click', function(e) {
                e.preventDefault();

                var uid = $(this).attr("data-uid");

                CampaignsSendTestEmailPopup.load();
            });
        });

        var CampaignsSendTestEmailPopup = {
            popup: null,

            url: '{{ action('CampaignController@sendTestEmail', [
                'uid' => $campaign->uid,
            ]) }}',

            load: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: this.url
                    });
                }
                this.popup.load();
            }
        }

        var CampaignsConfirmPreviewAs = {
            popup:  null,
            url: '{{ action('CampaignController@previewAs', [
                'uid' => $campaign->uid,
            ]) }}',

            getPopup: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: this.url,
                    });
                }

                return this.popup;
            },

            showPopup: function() {
                this.getPopup().load();
            }
        }

        $(function() {
            $('.preview-as-button').on('click', function() {
                CampaignsConfirmPreviewAs.showPopup();
            });
        });
    </script>
@endsection
