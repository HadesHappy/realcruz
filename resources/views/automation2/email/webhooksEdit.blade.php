@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.webhooks.edit') }}
@endsection

@section('content')
    <form class="edit-webhook-form" action="{{ action('Automation2Controller@webhooksEdit', [
        'webhook_uid' => $webhook->uid,
    ]) }}"
        method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-12">
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
            <button type="submit" class="btn btn-secondary edit-webhook-save">{{ trans('messages.save') }}</button>
            <button type="button" class="btn btn-light close ms-1">{{ trans('messages.close') }}</button>
        </div>
    </form>

    <script>
        var EmailWebhooksEdit = {
            save: function() {
                addButtonMask($('.edit-webhook-save'));
                var data = $('.edit-webhook-form').serialize();
                var url = $('.edit-webhook-form').attr('action');

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

                    EmailWebhooksList.getEditPopup().hide();
                    EmailWebhooks.loadList();
                }).fail(function(jqXHR, textStatus, errorThrown){
                    EmailWebhooksList.getEditPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    removeButtonMask($('.edit-webhook-save'));
                });
            }
        };

        $(function() {
            $('.edit-webhook-form').on('submit', function(e) {
				e.preventDefault();

                EmailWebhooksEdit.save();
			});
        })
    </script>
@endsection