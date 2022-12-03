@extends('layouts.popup.medium')

@section('title')
    <i class="material-symbols-rounded alert-icon mr-2">backup_table</i>
    {{ trans("messages.template") }}
@endsection

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h4 class="">{{ trans('messages.campaign.upload_custom_template') }}</h4>
                
            @include('campaigns.template._tabs')
            
            <p>{!! trans('messages.template.upload.instruction', ["link" => url('/download/Sample-Template.zip') ]) !!}</p>

            <div class="alert alert-info">
                {{ trans('messages.template.upload.warning') }}
            </div>
            
            <form enctype="multipart/form-data" action="{{ action('CampaignController@templateUpload', $campaign->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                <input type="hidden" name="name" value="{{ $campaign->name }}" />
                <input type="hidden" name="type" value="{{ Acelle\Model\Template::TYPE_EMAIL }}" />

                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file'])
				
                <div class="mt-20">
                    <button class="btn btn-primary bg-grey-600 me-1">{{ trans('messages.upload') }}</button>
                </div>

            </form>
        </div>
    </div>
        
    <script>
        $('a.choose-template-tab').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
        
            templatePopup.load(url);
        });

        var builderSelectPopup = new Popup({onclose: function() {
            window.location = '{{ action('CampaignController@template', $campaign->uid) }}';
        }});

        $('.ajax_upload_form').submit(function(e) {
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
                statusCode: {
                    // validate error
                    400: function (res) {
                        alert('Something went wrong!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                    removeMaskLoading();
                },
                success: function (response) {
                    removeMaskLoading();

                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});

                    builderSelectPopup.load(response.url);
                    templatePopup.hide();
                }
            });
        });
    </script>
@endsection