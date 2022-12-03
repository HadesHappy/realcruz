@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.webhook.test') }}
@endsection

@section('content')
    <form class="test-webhook-form" action="{{ action('Automation2Controller@webhooksTest', [
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
                    'label' => trans('messages.webhook.test.desc'),
                    'rules' => ['endpoint' => 'required'],
                    'readonly' => true,
                ])
            </div>
        </div>

        @if ($result)
            @if ($result['status'] == 'error')
                <div class="alert alert-danger">
                    {{ $result['message'] }}
                </div>
            @elseif ($result['status'] == 'sent')
                @php
                    $status = $result['code'] < 200 ? 'info' : ($result['code'] < 300 ? 'success' : ($result['code'] < 400 ? 'info' : ($result['code'] < 600 ? 'danger' : '')));
                @endphp
                <div class="mb-4">
                    <span class="badge rounded badge-lg py-2 badge-{{ $status }}">{{ $result['code'] }}</span>
                    <span class="">{{ $result['message'] }}</span>
                </div>
            @endif
        @endif

        <div class="mt-2">
            <button type="submit" class="btn btn-secondary test-webhook-save">{{ trans('messages.webhook.test') }}</button>
            <a class="btn btn-light ms-1 edit-webhook2"
                href="{{ action('Automation2Controller@webhooksEdit', [
                    'webhook_uid' => $webhook->uid,
                ]) }}">
                    <i class="material-symbols-rounded">edit</i>
                    {{ trans('messages.edit') }}
            </a>
            <button type="button" class="btn btn-light close ms-1">{{ trans('messages.close') }}</button>
        </div>
    </form>

    <script>
        var EmailWebhooksTest = {
            save: function() {
                addButtonMask($('.test-webhook-save'));
                var data = $('.test-webhook-form').serialize();
                var url = $('.test-webhook-form').attr('action');

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    EmailWebhooksList.getTestPopup().loadHtml(response);
                }).fail(function(jqXHR, textStatus, errorThrown){
                    EmailWebhooksList.getTestPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    removeButtonMask($('.test-webhook-save'));
                });
            }
        };

        $(function() {
            $('.test-webhook-form').on('submit', function(e) {
				e.preventDefault();

                EmailWebhooksTest.save();
			});

            $('.edit-webhook2').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                EmailWebhooksList.getTestPopup().hide();
                EmailWebhooksList.getEditPopup().load(url);
            });
        })
    </script>
@endsection