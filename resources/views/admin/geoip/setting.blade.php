@extends('layouts.popup.small')

@section('title')
    GeoIp Setting
@endsection

@section('content')
    <form id="GeoIpSetting" class="billing-address-form form-validate-jquery" action="{{ action('Admin\GeoIpController@setting') }}"
        method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-10">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'source',
                    'label' => 'Enter database source',
                    'value' => '',
                    'required' => true,
                    'rules' => ['source' => 'required'],
                ])
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-secondary">{{ trans('messages.save') }}</button>
            <button type="button" class="btn btn-link" onclick="GoeIpIndex.settingPopup.hide();">{{ trans('messages.cancel') }}</button>
        </div>
    </form>

    <script>
        var GeoIpSetting = {
            getUrl: function() {
                return $('#GeoIpSetting').attr('action');
            },
            
            getData: function() {
                return $('#GeoIpSetting').serialize();
            },

            submit: function(url, data) {
                var _this = this;
                addMaskLoading('Updating GeoIp setting...');

                $.ajax({
                    url: _this.getUrl(),
                    method: 'POST',
                    data: _this.getData(),
                    globalError: false,
                    statusCode: {
                        400: function (response) {
                            // validation
                            GoeIpIndex.settingPopup.loadHtml(response.responseText);
                        }
                    },
                    success: function (response) {
                        GoeIpIndex.settingPopup.hide();
                
                        // notify
                        notify(response.status, '{{ trans('messages.notify.success') }}', response.message); 
                    }
                }).always(function() {
                    removeMaskLoading();
                });
            }
        }

        $(document).ready(function() {
            $('#GeoIpSetting').on('submit', function(e) {
                e.preventDefault();

                GeoIpSetting.submit();
            })
        });
    </script>
@endsection