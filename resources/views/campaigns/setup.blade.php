@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.setup'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

    <link href="{{ URL::asset('core/emoji-picker/css/emoji.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ URL::asset('core/emoji-picker/js/config.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/emoji-picker/js/util.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/emoji-picker/js/jquery.emojiarea.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/emoji-picker/js/emoji-picker.js') }}"></script>
    
@endsection
	
@section('page_header')
	
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded me-2 me-2">
forward_to_inbox
</span> {{ $campaign->name }}</span>
		</h1>

		@include('campaigns._steps', ['current' => 2])
	</div>

@endsection

@section('content')
	<form action="{{ action('CampaignController@setup', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}
		
		<div class="row">
			<div class="col-md-6 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
				@include('helpers.form_control', ['type' => 'text',
					'name' => 'name',
					'label' => trans('messages.name_your_campaign'),
					'value' => $campaign->name,
					'rules' => $rules,
					'help_class' => 'campaign'
				])
				
				<div class="has-emoji">
					@include('helpers.form_control', ['type' => 'text',
						'name' => 'subject',
						'label' => trans('messages.email_subject'),
						'value' => $campaign->subject,
						'rules' => $rules,
						'help_class' => 'campaign',
						'attributes' => [
							'data-emojiable' => 'true',
						]
					])
				</div>
												
				@include('helpers.form_control', ['type' => 'text',
					'name' => 'from_name',
					'label' => trans('messages.from_name'),
					'value' => $campaign->from_name,
					'rules' => $rules,
					'help_class' => 'campaign'
				])
				
				<div class="hiddable-box" data-control="[name=use_default_sending_server_from_email]" data-hide-value="1">
					@include('helpers.form_control', [
						'type' => 'autofill',
						'id' => 'sender_from_input',
						'name' => 'from_email',
						'label' => trans('messages.from_email'),
						'value' => $campaign->from_email,
						'rules' => $rules,
						'help_class' => 'campaign',
						'url' => action('SenderController@dropbox'),
						'empty' => trans('messages.sender.dropbox.empty'),
						'error' => trans('messages.sender.dropbox.error.' . Auth::user()->customer->allowUnverifiedFromEmailAddress(), [
							'sender_link' => action('SenderController@index'),
						]),
						'header' => trans('messages.verified_senders'),
					])
				</div>

				@include('helpers.form_control', ['type' => 'checkbox2',
					'name' => 'use_default_sending_server_from_email',
					'label' => trans('messages.use_sending_server_default_value'),
					'value' => $campaign->use_default_sending_server_from_email,
					'rules' => $rules,
					'help_class' => 'campaign',
					'options' => ['0','1'],
				])
												
				@include('helpers.form_control', [
                    'type' => 'autofill',
                    'id' => 'sender_reply_to_input',
                    'name' => 'reply_to',
                    'label' => trans('messages.reply_to'),
                    'value' => $campaign->reply_to,
                    'url' => action('SenderController@dropbox'),
                    'rules' => $campaign->rules(),
                    'help_class' => 'campaign',
                    'empty' => trans('messages.sender.dropbox.empty'),
                    'error' => trans('messages.sender.dropbox.reply.error.' . Auth::user()->customer->allowUnverifiedFromEmailAddress(), [
                        'sender_link' => action('SenderController@index'),
                    ]),
                    'header' => trans('messages.verified_senders'),
                ])
				
			</div>
			<div class="col-md-6 segments-select-box">
				<div class="form-group checkbox-right-switch">
					@if ($campaign->type != 'plain-text')
						@include('helpers.form_control', ['type' => 'checkbox',
													'name' => 'track_open',
													'label' => trans('messages.track_opens'),
													'value' => $campaign->track_open,
													'options' => [false,true],
													'help_class' => 'campaign',
													'rules' => $rules
												])
					
						@include('helpers.form_control', ['type' => 'checkbox',
													'name' => 'track_click',
													'label' => trans('messages.track_clicks'),
													'value' => $campaign->track_click,
													'options' => [false,true],
													'help_class' => 'campaign',
													'rules' => $rules
												])
					@endif
					
					@include('helpers.form_control', ['type' => 'checkbox',
													'name' => 'sign_dkim',
													'label' => trans('messages.sign_dkim'),
													'value' => $campaign->sign_dkim,
													'options' => [false,true],
													'help_class' => 'campaign',
													'rules' => $rules
												])
					@include('helpers.form_control', [
						'type' => 'checkbox',
						'name' => 'custom_tracking_domain',
						'label' => trans('messages.custom_tracking_domain'),
						'value' => Auth::user()->customer->isCustomTrackingDomainRequired() ? true : $campaign->tracking_domain_id,
						'options' => [false,true],
						'help_class' => 'campaign',
						'rules' => $rules
					])
					
					<div class="select-tracking-domain">
						@include('helpers.form_control', [
							'type' => 'select',
							'name' => 'tracking_domain_uid',
							'label' => '',
							'value' => $campaign->trackingDomain? $campaign->trackingDomain->uid : null,
							'options' => Auth::user()->customer->getVerifiedTrackingDomainOptions(),
							'include_blank' => trans('messages.campaign.select_tracking_domain'),
							'help_class' => 'campaign',
							'rules' => $rules
						])
					</div>
												
					@if ($campaign->type == 'plain-text')
						<div class="alert alert-warning">
							{!! trans('messages.campaign.plain_text.open_click_tracking_wanring') !!}
						</div>
					@endif
					
					@if ($campaign->template)
						<div class="webhooks-management">
							<div class="d-flex align-items-center mb-2">
                                <h3 class="mb-0 me-2"> {{ trans('messages.webhooks') }}</h3>
                                <span class="badge badge-info">{{ number_with_delimiter($campaign->campaignWebhooks()->count()) }}</span>
                            </div>
							<div class="d-flex">
								<p>{{ trans('messages.webhooks.wording') }}</p>
								<div class="ms-4">
									<a href="javascript:;" class="btn btn-secondary manage_webhooks_but">
										{{ trans('messages.webhooks.manage') }}
									</a>
								</div>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
		<hr>
		<div class="text-end {{ Auth::user()->customer->allowUnverifiedFromEmailAddress() ? '' : 'unverified_next_but' }}">
			<button class="btn btn-secondary">{{ trans('messages.save_and_next') }} <span class="material-symbols-rounded">
arrow_forward
</span> </button>
		</div>
		
	<form>
	
	<script>
		var CampaignsSetup = {
			webhooksPopup: null,
			getWebhooksPopup: function() {
				if (this.webhooksPopup == null) {
					this.webhooksPopup = new Popup({
						url: '{{ action('CampaignController@webhooks', [
							'uid' => $campaign->uid,
						]) }}',
						onclose: function() {
							CampaignsSetup.refresh();
						}
					});
				}

				return this.webhooksPopup;
			},

			refresh: function() {
                $.ajax({
                    url: "",
                    method: 'GET',
                    data: {
                        _token: CSRF_TOKEN
                    },
                    success: function (response) {
                        var html = $('<div>').html(response).find('.webhooks-management').html();

                        $('.webhooks-management').html(html);
                    }
                });
            }
		}

		var CampaignsSetupNextButton = {
			manager: null,

			getManager: function() {
				if (this.manager == null) {
					this.manager = new GroupManager();
					this.manager.add({
						isError: function() {
							return $('.autofill-error:visible').length;
						},
						nextButton: $('.unverified_next_but'),
						inputs: $('[name=reply_to], [name=from_email]')
					});

					this.manager.bind(function(group) {
						group.check = function() {
							if (!group.isError()) {
								group.nextButton.removeClass('pointer-events-none');
								group.nextButton.removeClass('disabled');
							} else {
								group.nextButton.addClass('pointer-events-none');
								group.nextButton.addClass('disabled');
							}
						}

						group.check();

						group.inputs.on('change keyup', function() {
							group.check();
						});
					});
				}

				return this.manager;
			},

			check: function() {
				this.getManager().groups.forEach(function(group) {
					group.check();
				});
			}
		}

		$(function() {
			// check next button
			CampaignsSetupNextButton.check();

			// manage webhooks button click
			$('.manage_webhooks_but').on('click', function(e) {
				e.preventDefault();

				CampaignsSetup.getWebhooksPopup().load();
			});

			// @Legacy
			// auto fill
			var box = $('#sender_from_input').autofill({
				messages: {
					header_found: '{{ trans('messages.sending_identity') }}',
					header_not_found: '{{ trans('messages.sending_identity.not_found.header') }}'
				},
				callback: function() {
					CampaignsSetupNextButton.check();
				}
			});
			box.loadDropbox(function() {
				$('#sender_from_input').focusout();
				box.updateErrorMessage();
			})

			// auto fill 2
			var box2 = $('#sender_reply_to_input').autofill({
				messages: {
					header_found: '{{ trans('messages.sending_identity') }}',
					header_not_found: '{{ trans('messages.sending_identity.reply.not_found.header') }}'
				},
				callback: function() {
					CampaignsSetupNextButton.check();
				}
			});
			box2.loadDropbox(function() {
				$('#sender_reply_to_input').focusout();
				box2.updateErrorMessage();
			})

			$('[name="from_email"]').blur(function() {
				$('[name="reply_to"]').val($(this).val()).change();
			});
			$('[name="from_email"]').change(function() {
				$('[name="reply_to"]').val($(this).val()).change();
			});

			// select custom tracking domain
			$('[name=custom_tracking_domain]').change(function() {
				var value = $('[name=custom_tracking_domain]:checked').val();

				if (value) {
					$('.select-tracking-domain').show();
				} else {
					$('.select-tracking-domain').hide();
				}
			});
			$('[name=custom_tracking_domain]').change();

			// legacy
			$('.hiddable-box').each(function() {
				var box = $(this);
				var control = $(box.attr('data-control'));
				var hide_value = box.attr('data-hide-value');
				
				control.change(function() {            
					var val;
					
					control.each(function() {
						if ($(this).is(':checked')) {
							val = $(this).val();
						}
					});
					
					if(hide_value == val) {
						box.addClass('hide');
					} else {
						box.removeClass('hide');
					}
				});
				
				control.change();
			});

			$(function() {
				// Initializes and creates emoji set from sprite sheet
				window.emojiPicker = new EmojiPicker({
				emojiable_selector: '[data-emojiable=true]',
				assetsPath: 'http://onesignal.github.io/emoji-picker/lib/img/',
				popupButtonClasses: 'fa fa-smile-o'
				});
				// Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
				// You may want to delay this step if you have dynamically created input fields that appear later in the loading process
				// It can be called as many times as necessary; previously converted input fields will not be converted again
				window.emojiPicker.discover();
			});
		})
	</script>
				
@endsection
