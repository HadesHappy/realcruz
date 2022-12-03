<div class="sub_section">
    <h2 class="text-semibold my-4">{{ trans('messages.list.title.edit') }}</h2>
    <h3 class="text-semibold">{{ trans('messages.list_details') }}
    </h3>

    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'name',
                'value' => $list->name,
                'help_class' => 'list',
                'rules' => Acelle\Model\MailList::$rules,                    
            ])                
        </div>
        <div class="col-md-6">
            <div class="hiddable-cond" data-control="[name=use_default_sending_server_from_email]" data-hide-value="1">
                @include('helpers.form_control', [
                    'type' => 'autofill',
                    'id' => 'sender_from_input',
                    'name' => 'from_email',
                    'label' => trans('messages.from_email'),
                    'value' => $list->from_email,
                    'help_class' => 'list',
                    'rules' => Acelle\Model\MailList::$rules,
                    'url' => action('SenderController@dropbox'),
                    'empty' => trans('messages.sender.dropbox.empty'),
                    'error' => trans('messages.sender.dropbox.error', [
                        'sender_link' => action('SenderController@index'),
                    ]),
                    'header' => trans('messages.verified_senders'),
                ])
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
                @include('helpers.form_control', ['type' => 'text', 'name' => 'from_name', 'label' => trans('messages.default_from_name'), 'value' => $list->from_name, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
            <div class="has-emoji">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'default_subject',
                    'label' => trans('messages.default_email_subject'),
                    'value' => $list->default_subject, 'help_class' => 'list',
                    'rules' => Acelle\Model\MailList::$rules,
                    'attributes' => [
                        'data-emojiable' => 'true',
                    ]
                ])
            </div>
        </div>
    </div>
</div>

<div class="sub_section">
    <h3 class="text-semibold">
        {{ trans('messages.contact_information') }}
        <span class="subhead">{!! trans('messages.default_from_your_contact_information', ['link' => action('AccountController@contact')]) !!}</span>
    </h3>
    <div class="row">
        <div class="col-md-6">
                @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[company]', 'label' => trans('messages.company_organization'), 'value' => $list->contact->company, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
                @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[state]', 'label' => trans('messages.state_province_region'), 'value' => $list->contact->state, 'rules' => Acelle\Model\MailList::$rules])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[address_1]', 'label' => trans('messages.address_1'), 'value' => $list->contact->address_1, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[city]', 'label' => trans('messages.city'), 'value' => $list->contact->city, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[address_2]', 'label' => trans('messages.address_2'), 'value' => $list->contact->address_2, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[zip]', 'label' => trans('messages.zip_postal_code'), 'value' => $list->contact->zip, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'select', 'name' => 'contact[country_id]', 'label' => trans('messages.country'), 'value' => $list->contact->country_id, 'options' => Acelle\Model\Country::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[phone]', 'label' => trans('messages.phone'), 'value' => $list->contact->phone, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[email]', 'label' => trans('messages.email'), 'value' => $list->contact->email, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
        <div class="col-md-6">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'contact[url]', 'label' => trans('messages.url'), 'label' => trans('messages.home_page'), 'value' => $list->contact->url, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
        </div>
    </div>
</div>

<div class="sub_section">
    <h2 class="text-semibold">{{ trans('messages.settings') }}</h2>
    <h3 class="text-semibold">{{ trans('messages.subscription') }}</h3>
    <div class="row">
        {{-- <div class="col-md-6 hide">
            @include('helpers.form_control', ['type' => 'text', 'name' => 'email_subscribe', 'value' => $list->email_subscribe, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
            @include('helpers.form_control', ['type' => 'text', 'name' => 'email_unsubscribe', 'value' => $list->email_unsubscribe, 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
            <br />
        </div> --}}
        <div class="col-md-6">
            <div class="form-group checkbox-right-switch">
                @if ($allowedSingleOptin)
                    @include('helpers.form_control', [
                        'type' => 'checkbox',
                        'name' => 'subscribe_confirmation',
                        'value' => $list->subscribe_confirmation,
                        'options' => [false,true],
                        'help_class' => 'list',
                        'rules' => Acelle\Model\MailList::$rules
                    ])
                @else
                    <input type="hidden" name="subscribe_confirmation" value="1" />
                @endif
                @include('helpers.form_control', ['type' => 'checkbox', 'name' => 'unsubscribe_notification', 'value' => $list->unsubscribe_notification, 'options' => [false,true], 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group checkbox-right-switch">
                @include('helpers.form_control', ['type' => 'checkbox', 'name' => 'send_welcome_email', 'value' => $list->send_welcome_email, 'options' => [false,true], 'help_class' => 'list', 'rules' => Acelle\Model\MailList::$rules])
            </div>
        </div>
    </div>
</div>


@if (Auth::user()->customer->useOwnSendingServer())
    <div class="sub_section">
        <h3 class="text-semibold">{{ trans('messages.sending_servers') }}</h3>
        <div class="row mb-20 form-groups-bottom-0">
            <div class="col-md-3">
                @include('helpers.form_control', ['type' => 'checkbox2',
                    'class' => '',
                    'name' => 'all_sending_servers',
                    'value' => $list->all_sending_servers,
                    'label' => trans('messages.use_all_sending_servers'),
                    'options' => [false,true],
                    'help_class' => 'list',
                    'rules' => Acelle\Model\MailList::$rules
                ])
            </div>
        </div>
        @if(!\Auth::user()->customer->activeSendingServers()->count())
            <div class="alert alert-danger mt-3">
                {!! trans('messages.list.there_no_subaccount_sending_server') !!}
            </div>
        @else
            <div class="sending-servers">
                <hr>
                <div class="row text-muted text-semibold">
                    <div class="col-md-3">
                        <label>{{ trans('messages.select_sending_servers') }}</label>
                    </div>
                    <div class="col-md-3">
                        <label>{{ trans('messages.fitness') }}</label>
                    </div>
                </div>
                @foreach (\Auth::user()->customer->activeSendingServers()->orderBy("name")->get() as $server)
                    <div class="row mb-5 form-groups-bottom-0">
                        <div class="col-md-3">
                            @include('helpers.form_control', [
                                'type' => 'checkbox2',
                                'name' => 'sending_servers[' . $server->uid . '][check]',
                                'value' => $list->mailListsSendingServers->contains('sending_server_id', $server->id),
                                'label' => $server->name,
                                'options' => [false, true],
                                'help_class' => 'list',
                                'rules' => Acelle\Model\MailList::$rules
                            ])
                        </div>
                        <div class="col-md-3" show-with-control="input[name='{{ 'sending_servers[' . $server->uid . '][check]' }}']">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'class' => 'numeric',
                                'name' => 'sending_servers[' . $server->uid . '][fitness]',
                                'label' => '',
                                'value' => (is_object($list->mailListsSendingServers()->where('sending_server_id', $server->id)->first()) ? $list->mailListsSendingServers()->where('sending_server_id', $server->id)->first()->fitness : "100"),
                                'help_class' => 'list',
                                'rules' => Acelle\Model\MailList::$rules
                            ])
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            // all sending servers checking
            $(document).on("change", "input[name='all_sending_servers']", function(e) {
                if($("input[name='all_sending_servers']:checked").length) {
                    $(".sending-servers").find("input[type=checkbox]").each(function() {
                        if($(this).is(":checked")) {
                            $(this).parents(".form-group").find(".switchery").eq(1).click();
                        }
                    });
                    $(".sending-servers").hide();
                } else {
                    $(".sending-servers").show();
                }
            });
            $("input[name='all_sending_servers']").trigger("change");
        });
    </script>
@endif

<script>
    $(document).ready(function() {
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
        });
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
</script>
