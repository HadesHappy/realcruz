@extends('layouts.popup.small')

@section('title')
    {{ $campaign->name }}
@endsection

@section('content')

    <form id="sendTestEmail" sending-text='<i class="icon-spinner10 spinner position-left"></i> {{ trans('messages.sending_please_wait') }}'
        action="{{ action('CampaignController@sendTestEmail', [
            'uid' => $campaign->uid,
        ]) }}"
        method="POST"
    >
        {{ csrf_field() }}

        <div class="">

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
                <button id="testSend" type="submit" class="btn btn-primary me-1"><i class="icon-paperplane ml-5"></i> {{ trans('messages.send') }}</button>
                <a href="javascript:;" onclick="CampaignsSendTestEmailPopup.popup.hide()" role="button" class="btn btn-secondary">{{ trans('messages.close') }}</a>
            </div>

        </div>
    </form>

    <script>
        $('#sendTestEmail').on('submit', function(e) {
            e.preventDefault();
            var data = $(this).serialize();

            CampaignsSendTestEmail.submit(data);
        });

        var CampaignsSendTestEmail = {
            action: '{{ action('CampaignController@sendTestEmail', [
                'uid' => $campaign->uid,
            ]) }}',

            submit: function(data) {
                CampaignsSendTestEmailPopup.popup.mask();
                addButtonMask($('#testSend'));

                // copy
                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: response.status,
                        message: response.message,
                    });
                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for validation
                    CampaignsSendTestEmailPopup.popup.loadHtml(jqXHR.responseText);
                }).always(function() {
                    CampaignsSendTestEmailPopup.popup.unmask();
                    removeButtonMask($('#testSend'));
                });
            }
        }
    </script>
@endsection