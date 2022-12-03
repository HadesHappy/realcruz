@extends('layouts.popup.large')

@section('content')
        
    @include('automation2.email._tabs', ['tab' => 'setup'])
        
    <h5 class="mb-3">Email Setup</h5>    
    <p>{{ trans('messages.automation.email.setup.intro') }}</p>
    
    <form id="emailSetup" action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST">
        {{ csrf_field() }}
        
        <input type="hidden" name="email_uid" value="{{ $email->uid }}" />
        <input type="hidden" name="action_id" value="{{ $email->action_id }}" />
    
        <div class="row">
            <div class="col-md-6">          
                <div class="has-emoji">                                  
                    @include('helpers.form_control', ['type' => 'text',
                        'name' => 'subject',
                        'label' => trans('messages.email_subject'),
                        'value' => $email->subject,
                        'rules' => $email->rules(),
                        'help_class' => 'email',
                        'placeholder' => trans('messages.automation.email.subject.placeholder'), 
                        'attributes' => [
                            'data-emojiable' => 'true',
                        ]
                    ])
                </div>

                @include('helpers.form_control', ['type' => 'text',
                    'name' => 'from_name',
                    'label' => trans('messages.from_name'),
                    'value' => $email->from_name,
                    'rules' => $email->rules(),
                    'help_class' => 'email',
                    'placeholder' => trans('messages.automation.email.from_name.placeholder'), 
                ])
                
                @include('helpers.form_control', [
                    'type' => 'autofill',
                    'id' => 'sender_from_input',
                    'name' => 'from_email',
                    'label' => trans('messages.from_email'),
                    'value' => $email->from_email,
                    'rules' => $email->rules(),
                    'help_class' => 'email',
                    'url' => action('SenderController@dropbox'),
                    'empty' => trans('messages.sender.dropbox.empty'),
                    'error' => trans('messages.sender.dropbox.error.' . Auth::user()->customer->allowUnverifiedFromEmailAddress(), [
                        'sender_link' => action('SenderController@index'),
                    ]),
                    'header' => trans('messages.verified_senders'),
                    'placeholder' => trans('messages.automation.email.from.placeholder'), 
                ])
                                                
                @include('helpers.form_control', [
                    'type' => 'autofill',
                    'id' => 'sender_reply_to_input',
                    'name' => 'reply_to',
                    'label' => trans('messages.reply_to'),
                    'value' => $email->reply_to,
                    'url' => action('SenderController@dropbox'),
                    'rules' => $email->rules(),
                    'help_class' => 'email',
                    'empty' => trans('messages.sender.dropbox.empty'),
                    'error' => trans('messages.sender.dropbox.reply.error.' . Auth::user()->customer->allowUnverifiedFromEmailAddress(), [
                        'sender_link' => action('SenderController@index'),
                    ]),
                    'header' => trans('messages.verified_senders'),
                    'placeholder' => trans('messages.automation.email.from.placeholder'), 
                ])
                
            </div>
            <div class="col-md-6 segments-select-box">
                <div class="form-group checkbox-right-switch">
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'track_open',
                        'label' => trans('messages.automation.email.track_open'),
                        'value' => $email->track_open,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'track_click',
                        'label' => trans('messages.automation.email.track_click'),
                        'value' => $email->track_click,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                    
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'sign_dkim',
                        'label' => trans('messages.automation.email.add_sign_dkim'),
                        'value' => $email->sign_dkim,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                    @include('helpers.form_control', [
                        'type' => 'checkbox3',
                        'name' => 'custom_tracking_domain',
                        'label' => trans('messages.custom_tracking_domain'),
                        'value' => Auth::user()->customer->isCustomTrackingDomainRequired() ? true : ($email->tracking_domain_id || request()->custom_tracking_domain),
                        'options' => [false,true],
                        'help_class' => 'email',
                        'readonly' => Auth::user()->customer->isCustomTrackingDomainRequired(),
                        'rules' => $email->rules()
                    ])

                    <div class="select-tracking-domain">
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'tracking_domain_uid',
                            'label' => '',
                            'value' => $email->trackingDomain? $email->trackingDomain->uid : null,
                            'options' => Auth::user()->customer->getVerifiedTrackingDomainOptions(),
                            'include_blank' => trans('messages.automation.email.select_tracking_domain'),
                            'help_class' => 'email',
                            'rules' => $email->rules()
                        ])
                    </div>

                    @if ($email->template)
						<div class="webhooks-management">
							<div class="d-flex align-items-center mb-2">
                                <h3 class="mb-0 me-2"> {{ trans('messages.webhooks') }}</h3>
                                <span class="badge badge-info">{{ number_with_delimiter($email->emailWebhooks()->count()) }}</span>
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
        
        <div class="text-end mt-5 {{ Auth::user()->customer->allowUnverifiedFromEmailAddress() ? '' : 'unverified_next_but' }}">
            <button class="btn btn-secondary">
                <span class="d-flex align-items-center">
                    <span>{{ trans('messages.email.setup.save_next') }}</span> <i class="material-symbols-rounded">keyboard_arrow_right</i>
                </span>
            </button>
        </div>
    </form>
    
    <script>
        @if ($email->template)
            var EmailSetup = {
                webhooksPopup: null,
                getWebhooksPopup: function() {
                    if (this.webhooksPopup == null) {
                        this.webhooksPopup = new Popup({
                            url: '{{ action('Automation2Controller@webhooks', [
                                'email_uid' => $email->uid,
                            ]) }}',
                            onclose: function() {
                                EmailSetup.refresh();
                            }   
                        });
                    }

                    return this.webhooksPopup;
                },

                refresh: function() {
                    $.ajax({
                        url: "{{ action('Automation2Controller@emailSetup', [
                            'uid' => $automation->uid,
                            'email_uid' => $email->uid,
                        ]) }}",
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

            $(function() {
                // manage webhooks button click
                $('.manage_webhooks_but').on('click', function(e) {
                    e.preventDefault();

                    EmailSetup.getWebhooksPopup().load();
                });
            });
        @endif

        function checkUnverified() {
			if(!$('.autofill-error:visible').length) {
				$('.unverified_next_but').removeClass('pointer-events-none');
                $('.unverified_next_but').removeClass('disabled');
			} else {
				$('.unverified_next_but').addClass('pointer-events-none');
                $('.unverified_next_but').addClass('disabled');
			}
		}

        setInterval(function() { checkUnverified() }, 1000);

        // auto fill
        var box = $('#sender_from_input').autofill({
            messages: {
                header_found: '{{ trans('messages.sending_identity') }}',
                header_not_found: '{{ trans('messages.sending_identity.not_found.header') }}'
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
            }
        });
        box2.loadDropbox(function() {
            $('#sender_reply_to_input').focusout();
            box2.updateErrorMessage();
        })
        
        $('#emailSetup').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            
            // loading effect
            popup.loading();
            
            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                       popup.loadHtml(res.responseText);
                    }
                 },
                 success: function (response) {
                    popup.load(response.url);
                    
                    // set node title
                    tree.getSelected().setTitle(response.title);
                    // merge options with reponse options
                    tree.getSelected().setOptions($.extend(tree.getSelected().getOptions(), {init: "true"}));
                    tree.getSelected().setOptions($.extend(tree.getSelected().getOptions(), response.options));

                    doSelect(tree.getSelected());

                    // validate
					tree.getSelected().validate();
                    
                    // save tree
					saveData();
                    
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                 }
            });
        });

        $('[name="from_email"]').change(function() {
            $('[name="reply_to"]').val($(this).val()).change();
        });
        $('[name="from_email"]').blur(function() {
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
    </script>
@endsection
