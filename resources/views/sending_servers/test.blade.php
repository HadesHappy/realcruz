@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.test_sending_server') }}
@endsection

@section('content')

    <form id="TestEmailForm" action="" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="">
        <input type="hidden" name="uids" value="">

        @foreach (request()->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="">
            <p>{{ trans('messages.test_sending_server.intro') }}</p>
            @include('helpers.form_control', [
                'type' => 'autofill',
                'id' => 'sender_from_input',
                'class' => 'email',
                'name' => 'from_email',
                'value' => '',
                'label' => trans('messages.from_email'),
                'rules' => ['from_email' => 'required'],
                'help_class' => 'campaign',
                'url' => action('SendingServerController@fromDropbox', $server->uid),
                'empty' => trans('messages.sender.dropbox.empty'),
                'error' => trans('messages.sender.dropbox.error', [
                    'sender_link' => action('SendingServerController@index'),
                ]),
                'header' => trans('messages.verified_senders'),
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
        </div>
        <div class="mt-4 text-left">
            <button type="submit"
                href="javascript:;"
                role="button"
                class="btn btn-secondary me-1"
            >
                {{ trans('messages.send') }}
            </button>
            <button role="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.close') }}</button>
        </div>
    </form>
        
    <script>
        var TestEmail = {
            url: '{{ action('SendingServerController@test', $server->uid) }}',
            getData: function() {
                return $('#TestEmailForm').serialize();
            },

            run: function() {
                SendTestEmail.getPopup().mask();
                addMaskLoading();

                // copy
                $.ajax({
                    url: this.url,
                    type: 'POST',
                    data: this.getData(),
                    globalError: false
                }).done(function(response) {
                    new Dialog('alert', {
                        title: LANG_SUCCESS,
                        message: response.message,
                    });

                    SendTestEmail.getPopup().unmask();
                    removeMaskLoading();
                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for debugging
                    new Dialog('alert', {
                        title: LANG_ERROR,
                        message: JSON.parse(jqXHR.responseText).message,
                    });
                    
                    SendTestEmail.getPopup().unmask();
                    removeMaskLoading();
                }).always(function() {
                    SendTestEmail.getPopup().unmask();
                    removeMaskLoading();
                });
            }
        }

        $(function() {
            $('#TestEmailForm').on('submit', function(e) {
                e.preventDefault();

                if ($(this).valid()) {
                    TestEmail.run();
                }
                
                return false;
            });

            // auto fill
            var box = $('#sender_from_input').autofill({
                messages: {
                    header_found: '{{ trans('messages.sending_identity') }}',
                    header_not_found: '{{ trans('messages.sending_identity.not_found.header') }}'
                }
            });
            box.loadDropbox(function() {
            })
        })
    </script>
@endsection