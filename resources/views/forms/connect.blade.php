@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.form.connect.title') }}
@endsection

@section('content')
    <form id="FormConnectSite" action="" method="POST">
        {{ csrf_field() }}

        <p class="mb-3">
            {{ trans('messages.form.connect.select_site') }}
        </p>

        @include('helpers.form_control', [
            'type' => 'select',
            'name' => 'website_uid',
            'include_blank' => '-' . trans('messages.form.choose_site') . '-',
            'label' => '',
            'value' => $form->getMetadata('website_uid'),
            'options' => Auth::user()->customer->getConnectedWebsiteSelectOptions(),
            'rules' => [],
        ])

        
        @if ($form->getMetadata('website_uid'))
            <button type="button" class="btn btn-secondary connect-site">{{ trans('messages.form.connect') }}</button>
            <button type="button" class="btn btn-default remove-site">{{ trans('messages.form.remove_site') }}</button>
        @else
            <button type="button" class="btn btn-secondary connect-site">{{ trans('messages.form.connect') }}</button>
        @endif
            <a target="_blank" href="{{ action('WebsiteController@index') }}" class="ms-1 btn btn-link">
                {{ trans('messages.sites_management') }}
            </a>
    </form>

    <script>
        var FormsConnect = {
            connectUrl: '{{ action('FormController@connect', [
                'uid' => $form->uid,
            ]) }}',
            removeUrl: '{{ action('FormController@disconnect', [
                'uid' => $form->uid,
            ]) }}',
            getForm: function() {
                return $('#FormConnectSite');
            },

            remove: function() {
                $.ajax({
                    url: this.removeUrl,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN
                    }
                })
                .done(function(res) {
                    removeMaskLoading();
                    
                    notify({
                        message: res.message
                    });

                    FormsEdit.getConnectPopup().hide();

                    FormsEdit.getFormsBuilder().refreshAddressBar();
                });
            },

            save: function(url) {
                var _this = this;
                var data = this.getForm().serialize();

                addMaskLoading();

                // 
                $.ajax({
                    url: this.connectUrl,
                    method: 'POST',
                    data: data,
                    globalError: false
                })
                .done(function(res) {
                    removeMaskLoading();
                    
                    notify({
                        message: res.message
                    });

                    FormsEdit.getConnectPopup().hide();

                    FormsEdit.getFormsBuilder().refreshAddressBar();
                })
                .fail(function(res) {
                    switch (res.status) {
                        case 400:
                            FormsEdit.getConnectPopup().loadHtml(res.responseText);
                            removeMaskLoading();
                            break;
                        default:
                            alert(res.responseText);
                    }
                    
                });
            }
        };

        $(function() {
            $('.connect-site').on('click', function(e) {
                e.preventDefault();        

                FormsConnect.save();
            });

            $('.remove-site').on('click', function(e) {
                e.preventDefault();        

                FormsConnect.remove();
            });
        })
    </script>
@endsection