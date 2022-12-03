@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.billing_information') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-semibold">{{ trans('messages.sending_server.add_email_domain') }}</h2>

            <div class="mb-20">
                <div class="row">
                    <div class="col-md-12">                                
                        <p>{!! trans('messages.sending_server.add_email_domain.wording') !!}</p>
                            
                        <form id="addDomain" action="{{ action('Admin\SendingServerController@addDomain', $server->uid) }}" method="POST" class="form-validate-jquery">
                            {{ csrf_field() }}
                            
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'name' => 'domain',
                                'label' => trans('messages.email_domain'),
                                'value' => isset($domain) ? $domain : '',
                                'help_class' => 'domain',
                                'rules' => ['domain' => 'required']
                            ])
                            
                            <button type="submit" class="btn btn-primary bg-grey-600">
                                {{ trans('messages.sending_server.add_email_domain') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(function() {
            $('#addDomain').submit(function() {
                var  form = $(this);
                
                $.ajax({
                    method: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize()
                })
                .done(function( data ) {
                    if ($('<div>').html(data).find('form').length) {
                        addDomain.loadHtml(data); 
                    } else {
                        location.reload();
                    }                
                });
            
                return false;
            });
        });
    </script>
@endsection