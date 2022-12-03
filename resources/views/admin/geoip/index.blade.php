@extends('layouts.core.backend')

@section('title', trans('messages.geoip.title'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.geoip.title') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <p>{{ trans('messages.geoip.description') }}</p>

    <div class="pml-table-container">
        <table class="table table-box pml-table">
            <tbody>
                <tr>
                    <td class="plugin-title-column plugin-title-acelle/postal">
                        <img class="plugin-icon" src="/p/assets/L2FwcC9wbHVnaW5zL2FjZWxsZS9wb3N0YWwvaWNvbi5zdmc%3D">
                        <h5 class="no-margin text-bold kq_search">
                            GeoLite2 Free Geolocation Data
                        </h5>
                        <span class="">
                            By Maxmind
                        </span>
                        <br>
                        <span class="text-muted">Version: 2020</span>
                    </td>
                    <td>
                        <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-active">
                        Active
                        </span>
                        </span>
                    </td>
                    <td class="text-right text-nowrap">
                        <a id="EditGeoIpSetting"
                            href="{{ action('Admin\GeoIpController@setting') }}"
                            class="btn btn-primary"
                        >
                            Setting
                        </a>
                        <a id="EditGeoIpReset" href="#" class="btn btn-secondary">
                        Reset
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex align-items-center">
            <div class="num_per_page mr-auto">
                <p><i>Powered by Acelle</i></p>
            </div>
        </div>
    </div>

    <script>
        var GoeIpIndex = {
            settingPopup: null,
            resetUrl: '{{ action('Admin\GeoIpController@reset') }}',

            loadSettingPopup: function() {
                this.settingPopup = new Popup();
                this.settingPopup.load('{{ action('Admin\GeoIpController@setting') }}');
            },

            reset: function() {
                var _this = this;
                var dialog = new Dialog('confirm', {
                    message: 'Are you sure you want to reset GeoIP database?',
                    ok: function() {
                        addMaskLoading('Reseting GeoIp...');
                        
                        $.ajax({
                            url: _this.resetUrl,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN
                            },
                            globalError: false
                        }).success(function(response) {
                            notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                        }).error(function(response) {
                            var error = JSON.parse(response.responseText).error;
                            notify('error', '{{ trans('messages.notify.error') }}', error);
                        }).always(function(response) {
                            removeMaskLoading();
                        });
                    }
                });
            },
        };

        $(document).ready(function() {
            $('#EditGeoIpSetting').on('click', function(e) {
                e.preventDefault();
                
                GoeIpIndex.loadSettingPopup();
            });

            $('#EditGeoIpReset').on('click', function(e) {
                e.preventDefault();

                GoeIpIndex.reset();
            });
        });

    </script>
@endsection
