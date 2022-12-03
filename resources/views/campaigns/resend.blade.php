@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mt-0">{{ trans('messages.campaign.resend.title') }}</h2>
            <p>{{ trans('messages.campaign.resend.intro') }}</p>   

            <form enctype="multipart/form-data" action="{{ action('CampaignController@resend', $campaign->uid) }}" method="POST" class="resend-form form-validate-jqueryx">
                {{ csrf_field() }}

                @include('helpers.form_control', ['type' => 'radio',
					'name' => 'option',
					'label' => '',
					'value' => 'not_receive',
                    'rules' => ['option' => 'required'],
                    'options' => [
                        ['text' => trans('messages.campaign.resend.option.not_receive'), 'value' => 'not_receive'],
                        ['text' => trans('messages.campaign.resend.option.not_open'), 'value' => 'not_open'],
                        ['text' => trans('messages.campaign.resend.option.not_click'), 'value' => 'not_click'],
                    ],
                    'help_class' => 'campaign',
				])
                <hr>
                <div class="text-center">
                    <button class="btn btn-secondary bg-grey mt-3 mr-2">{{ trans('messages.campaign.resend') }}</button>
                    <a href="javascript:;" onclick="CampaignsResendPopup.popup.hide()" class="btn btn-link font-weight-semibold mt-3">{{ trans('messages.campaign.resend.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('.resend-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                dataType: "json",
                statusCode: {
                    // validate error
                    400: function (res) {
                        // notify
                        notify('error', '{{ trans('messages.notify.error') }}', res.responseText);
                    }
                },
                success: function (response) {
                    CampaignsResendPopup.popup.hide();

                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});

                    CampaignsIndex.getList().load();
                },
                globalError: false,
                error: function (res) {
                    // newSubscription.loadHtml(res.responseText);
                    // notify
                    alert(res.responseText);
                }
            });
        });
    </script>
@endsection