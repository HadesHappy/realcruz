@if (Auth::user()->admin->getPermission("setting_general") == 'yes')
    <div class="tab-pane active mailer-form" id="top-smtp">
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'env[MAIL_MAILER]',
                    'label' => trans('messages.mail_driver'),
                    'value' => (isset($env["MAIL_MAILER"]) ? $env["MAIL_MAILER"] : ""),
                    'options' => [["value" => "sendmail", "text" => trans('messages.sendmail')],["value" => "smtp", "text" => trans('messages.smtp')]],
                    'help_class' => 'env',
                    'rules' => $rules
                ])
            </div>
        </div>
        <div class="">
            <div class="row box mailer-setting smtp">
                <div class="col-md-6">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'env[MAIL_HOST]',
                        'label' => trans('messages.hostname'),
                        'value' => (isset($env["MAIL_HOST"]) ? $env["MAIL_HOST"] : ""),
                        'help_class' => 'env',
                        'rules' => $rules
                    ])
                </div>
                <div class="col-md-6">
                    <div class="row box">
                        <div class="col-md-6">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'name' => 'env[MAIL_PORT]',
                                'label' => trans('messages.port'),
                                'value' => (isset($env["MAIL_PORT"]) ? $env["MAIL_PORT"] : ""),
                                'help_class' => 'env',
                                'rules' => $rules
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'name' => 'env[MAIL_ENCRYPTION]',
                                'label' => trans('messages.encryption'),
                                'value' => (isset($env["MAIL_ENCRYPTION"]) ? $env["MAIL_ENCRYPTION"] : ""),
                                'help_class' => 'env',
                                'rules' => $rules
                            ])
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row box  mailer-setting smtp">
                <div class="col-md-6">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'env[MAIL_USERNAME]',
                        'label' => trans('messages.username'),
                        'value' => (isset($env["MAIL_USERNAME"]) ? $env["MAIL_USERNAME"] : ""),
                        'help_class' => 'env',
                        'rules' => $rules
                    ])
                </div>
                <div class="col-md-6">
                    @include('helpers.form_control', [
                        'type' => 'password',
                        'name' => 'env[MAIL_PASSWORD]',
                        'label' => trans('messages.password'),
                        'value' => (isset($env["MAIL_PASSWORD"]) ? $env["MAIL_PASSWORD"] : ""),
                        'help_class' => 'env',
                        'eye' => true,
                        'rules' => $rules
                    ])
                </div>
            </div>
                
            <div class="row box  mailer-setting smtp sendmail">
                <div class="col-md-6">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'env[MAIL_FROM_ADDRESS]',
                        'label' => trans('messages.from_email'),
                        'value' => (isset($env["MAIL_FROM_ADDRESS"]) ? $env["MAIL_FROM_ADDRESS"] : ""),
                        'help_class' => 'env',
                        'rules' => $rules
                    ])
                </div>
                <div class="col-md-6">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'env[MAIL_FROM_NAME]',
                        'label' => trans('messages.from_name'),
                        'value' => (isset($env["MAIL_FROM_NAME"]) ? $env["MAIL_FROM_NAME"] : ""),
                        'help_class' => 'env',
                        'rules' => $rules
                    ])  
                </div>
                <div class="col-md-6 mailer-setting sendmail">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'env[sendmail_path]',
                        'label' => trans('messages.sendmail_path'),
                        'value' => (isset($env["sendmail_path"]) ? $env["sendmail_path"] : ""),
                        'help_class' => 'env',
                        'rules' => ['mailer.sendmail_path' => 'required']
                    ])  
                </div>
            </div>
        </div>
        
        <br />
        <div class="text-left">
            <button class="btn btn-secondary mr-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\SettingController@mailerTest') }}"
                class="btn btn-secondary send-test-email"
            >
                <i class="icon-envelope mr-2"></i> {{ trans('messages.setting.mailer.send_a_test_email') }}
            </a>
        </div>
    </div>

    <script>
        var testPopup = new Popup();
        var changed = false;

        $('.mailer-form input, .mailer-form select').change(function() {
            changed = true;
        });

        $('.send-test-email').click(function(e) {
            e.preventDefault();

            // something changed!
            if (changed) {
                var dialog = new Dialog('alert', {
                    message: '{{ trans('messages.setting.mailer.save_change_before_test') }}',
                });
                return;
            }

            var url = $(this).attr('href');

            testPopup.data = $('.mailer-form').serialize();
            testPopup.load({
                url: url,
                data: $('.mailer-form').serialize()
            });
        });
    </script>
@endif