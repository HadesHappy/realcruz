@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.webhooks.add_webhook') }}
@endsection

@section('content')
    <form class="add-webhook-form" action="{{ action('Automation2Controller@webhooksAdd', [
        'email_uid' => $email->uid,
    ]) }}"
        method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-12">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'type',
                    'value' => $webhook->type,
                    'label' => trans('messages.webhook.select_type'),
                    'options' => [
                        ['text' => trans('messages.webhook.type.open'), 'value' => Acelle\Model\CampaignWebhook::TYPE_OPEN],
                        ['text' => trans('messages.webhook.type.click'), 'value' => Acelle\Model\CampaignWebhook::TYPE_CLICK],
                        // ['text' => trans('messages.webhook.type.unsubscribe'), 'value' => Acelle\Model\CampaignWebhook::TYPE_UNSUBSCRIBE],
                    ],
                    'rules' => ['type' => 'required'],
                ])

                <div class="link_select"></div>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'endpoint',
                    'value' => $webhook->endpoint,
                    'label' => trans('messages.webhook.endpoint.desc'),
                    'rules' => ['endpoint' => 'required'],
                ])
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-secondary add-link-save">{{ trans('messages.save') }}</button>
            <button type="button" class="btn btn-light close ms-1">{{ trans('messages.close') }}</button>
        </div>
    </form>

    <script>
        var EmailWebhooksAdd = {
            getType: function() {
                return $('.add-webhook-form [name=type]').val();
            },
            
            showLinkSelect: function() {
                var box = new Box($('.link_select'), '{{ action('Automation2Controller@webhooksLinkSelect', [
                    'email_uid' => $email->uid,
                    'email_link_id' => $webhook->email_link_id,
                ]) }}');
                box.load();
            },

            hideLinkSelect: function() {
                $('.link_select').html('');
            },

            toggleLinkSelect: function() {
                if (this.getType() == '{{ Acelle\Model\CampaignWebhook::TYPE_CLICK }}') {
                    this.showLinkSelect();
                } else {
                    this.hideLinkSelect();
                }
            },

            save: function() {
                addButtonMask($('.add-link-save'));
                var data = $('.add-webhook-form').serialize();
                var url = $('.add-webhook-form').attr('action');

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: 'success',
                        message: response.message,
                    });

                    EmailWebhooks.getAddPopup().hide();
                    EmailWebhooks.loadList();
                }).fail(function(jqXHR, textStatus, errorThrown){
                    EmailWebhooks.getAddPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    removeButtonMask($('.add-link-save'));
                });
            }
        };

        $(function() {
            $('.add-webhook-form').on('submit', function(e) {
				e.preventDefault();

                EmailWebhooksAdd.save();
			});

            // toogle link select
            EmailWebhooksAdd.toggleLinkSelect();
            $('.add-webhook-form [name=type]').on('change', function(e) {
				EmailWebhooksAdd.toggleLinkSelect();
			});
        })
    </script>
@endsection