@extends('layouts.core.backend')

@section('title', trans('messages.install_plugin'))

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PluginController@index") }}">{{ trans('messages.plugins') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.install_plugin') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-6">
        
            <p class="">
                {{ trans('messages.plugin.upload.instruction') }}
            </p>
        
            <form enctype="multipart/form-data" action="{{ action('Admin\PluginController@install') }}"
                method="POST"
                class="plugin-form form-validate-jquery"
            >
                {{ csrf_field() }}
                
                @include('helpers.form_control', [
                    'required' => true,
                    'type' => 'file',
                    'label' => trans('messages.upload_file'),
                    'name' => 'file',
                ])
                    
                <div class="text-end">
                    <button class="btn btn-secondary mr-2">
                        {{ trans('messages.upload') }}
                    </button>
                    <a href="{{ action('Admin\PluginController@index') }}" class="btn btn-primary">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
                
            </form>
        </div>
    </div>

    <script>
        function doInstall(url, data) {
            addMaskLoading(`{!! trans('messages.plugin.installing') !!}`);

            $.ajax({
                url: url, 
                type: 'POST',
                data: data, // The form with the file inputs.
                processData: false,
                contentType: false,                    // Using FormData, no need to process data.
                globalError: false,
            }).done(function(res){
                window.location = res.url;
            }).fail(function(e){
                var error = JSON.parse(e.responseText).message;
                removeMaskLoading();

                if (error.includes('already exists')) {
                    var dialog = new Dialog('confirm', {
                        message: error,
                        ok: function(dialog) {       
                            data.append('overwrite', true);             
                            doInstall(url, data);
                        },
                        cancel: function(dialog) {

                        },
                    });
                    return;
                }

                notify({
                    title: "{{ trans('messages.notify.error') }}",
                    message: error,
                });
            });
        }
        $('.plugin-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var form = $(this);
            var data = new FormData(form[0]);

            if (form.valid()) {
                doInstall(url, data);
            }
        });
    </script>

@endsection
