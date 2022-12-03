@extends('layouts.popup.small')

@section('title')
    {{ $email->automation->name }}
@endsection

@section('content')

    <form id="sendTestEmailForm"
        method="POST"
    >
        {{ csrf_field() }}

            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'email',
                'class' => 'email',
                'value' => request()->has('email') ? request()->email : '',
                'label' => trans('messages.enter_an_email_address_for_testing_campaign'),
                'options' => Acelle\Library\Tool::timeUnitOptions(),
                'include_blank' => trans('messages.choose'),
                'help_class' => 'campaign',
                'rules' => ['send_test_email' => 'required']
            ])

            <div class="text-end">
                <button id="testSend" type="submit" class="btn btn-secondary me-1"><i class="icon-paperplane"></i> {{ trans('messages.send') }}</button>
                <a href="javascript:;" onclick="Automation2SendTestEmailPopup.popup.hide()" role="button" class="btn btn-secondary">{{ trans('messages.close') }}</a>
            </div>
    </form>

    <script>
        $('#sendTestEmailForm').on('submit', function(e) {
            e.preventDefault();
            var data = $(this).serialize();

            Automation2SendTestEmail.submit(data);
        });

        var Automation2SendTestEmail = {
            action: '{{ action('Automation2Controller@sendTestEmail', [
                'email_uid' => $email->uid,
            ]) }}',

            submit: function(data) {
                addMaskLoading();

                // copy
                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    Automation2SendTestEmailPopup.popup.hide();

                    new Dialog('alert', {
                        title: 'Success',
                        message: response.message
                    });
                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for validation
                    Automation2SendTestEmailPopup.popup.loadHtml(jqXHR.responseText);
                }).always(function() {
                    removeMaskLoading();
                });
            }
        }
    </script>
@endsection