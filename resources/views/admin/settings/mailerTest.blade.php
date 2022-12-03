@extends('layouts.popup.small')

@section('content')
    <h2 class="mt-0 mb-4 d-flex align-items-center">
        <span>{{ trans('messages.test_sending_server') }}</span>
    </h2>

    <form action="{{ action('Admin\SettingController@mailerTest') }}" method="POST" class="mailer-test-form form-validate-jquery">
        {{ csrf_field() }}

        @foreach (request()->env as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <p>{{ trans('messages.test_sending_server.intro') }}</p>
        @include('helpers.form_control', [
            'type' => 'text',
            'id' => 'sender_from_input',
            'class' => 'email',
            'name' => 'from_email',
            'value' => request()->env['MAIL_FROM_ADDRESS'],
            'disabled' => true,
            'label' => trans('messages.from_email'),
            'rules' => ['from_email' => 'required'],
            'help_class' => 'campaign',
        ])
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => 'email',
            'label' => trans('messages.to_email'),
            'name' => 'to_email',
            'value' => '',
            'help_class' => 'sending_server',
            'rules' => ['to_email' => 'required']
        ])
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'label' => trans('messages.subject'),
            'name' => 'subject',
            'value' => '',
            'help_class' => 'sending_server',
            'rules' => ['subject' => 'required']
        ])
        @include('helpers.form_control', [
            'type' => 'textarea',
            'class' => '',
            'label' => trans('messages.content'),
            'name' => 'content',
            'value' => '',
            'help_class' => 'sending_server',
            'rules' => ['content' => 'required']
        ])

        <div class="text-left">
            <button
                class="btn btn-secondary mr-2"
            >
                {{ trans('messages.send') }}
            </button>
            <button role="button" class="btn btn-primary" onclick="testPopup.hide()">{{ trans('messages.close') }}</button>
        </div>
    </form>

    <script>
        $('.mailer-test-form').submit(function(e) {
            e.preventDefault();

            var data = $(this).serialize();
            var url = $(this).attr('action');

            if ($(this).valid()) {
                addMaskLoading('{{ trans('messages.setting.mailer.sending_test_email') }}');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false,
                    error: function (response) {
                        removeMaskLoading();

                        var dialog = new Dialog('alert', {
                            title: '{{ trans('messages.notify.failed') }}',
                            message: JSON.parse(response.responseText).error,
                        });
                    },
                    success: function (response) {
                        removeMaskLoading();
                
                        var dialog = new Dialog('alert', {
                            title: '{{ trans('messages.notify.success') }}',
                            message: '{{ trans('messages.setting.mailer.email_sent') }}',
                        });
                    }
                });
            }
        });
    </script>
@endsection