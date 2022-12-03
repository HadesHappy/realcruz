@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form action="{{ action('Admin\SendingServerController@whitelabelSettings', $server->uid) }}" method="POST" class="whitelabel-form">
                {{ csrf_field() }}
                <h2 class="m-0 mb-3">{{ trans('messages.whitelabel.setup.title') }}</h2>
                <p>
                    {{ trans('messages.whitelabel.setup.intro') }}
                </p>

                <label class="font-weight-semibold text-muted">{{ trans('messages.whitelabel.choose_a_brand_domain') }}</label>
                <div class="row mb-4">
                    <div class="col-md-6 pr-0 form-groups-bottom-0">
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'class' => '',
                            'label' => '',
                            'name' => 'brand',
                            'value' => $server->getOption('whitelabel') ? $server->getOption('whitelabel')['brand'] : '',
                            'help_class' => 'whitelabel',
                            'rules' => ['brand' => 'required']
                        ])
                    </div>
                </div>
                <div class=" mt-4">
                    <button class="btn btn-secondary mr-3 whitelabel-save">{{ trans('messages.ok_enable') }}</button>
                    <button class="btn btn-link" onclick="whitelabelPopup.hide()">{{ trans('messages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('.whitelabel-form').submit(function(e) {
            e.preventDefault();        
            var url = $(this).attr('action');
            var formData = new FormData($(this)[0]);

            addMaskLoading();

            // 
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        whitelabelPopup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (response) {
                    removeMaskLoading();

                    // notify
                    notify(response.status, '{{ trans('messages.notify.success') }}', response.message); 

                    whitelabelPopup.hide();

                    whitelabelBox.load();
                }
            });
        });
    </script>
@endsection