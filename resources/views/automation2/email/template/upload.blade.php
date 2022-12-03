@extends('layouts.popup.large')

@section('content')
    
    @include('automation2.email._tabs', ['tab' => 'template'])
        
    <div class="row">
        <div class="col-md-12">
            @include('automation2.email.template._tabs', ['tab' => 'upload'])
            
            <h5 class="mb-3 mt-3">{{ trans('messages.automation.email.upload_template') }}</h4>
                
            <p>{!! trans('messages.template.upload.instruction', ["link" => url('/download/Sample-Template.zip') ]) !!}</p>

            <div class="alert alert-info">
                {{ trans('messages.template.upload.warning') }}
            </div>
                
            <form enctype="multipart/form-data" action="{{ action('Automation2Controller@templateUpload', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}" method="POST" class="template-upload-form">
                {{ csrf_field() }}

                <input type="hidden" name="type" value="{{ Acelle\Model\Template::TYPE_EMAIL }}" />
                <input type="hidden" name="name" value="{{ trans('messages.untitled') }}" />

                <div class="input-group mb-4 mt-4">
                    <div class="custom-file">
                      <input type="file" name="file" class="form-control custom-file-input" id="templateFile" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-primary bg-grey-600 me-1">{{ trans('messages.automation.email.template.upload') }}</button>
                </div>

            </form>
        </div>
    </div>
        
    <script>
        var builderSelectPopup = new Popup();
        
        $('.template-upload-form').submit(function(e) {
            e.preventDefault();
            
            if (!$('#templateFile').val()) {
                notify('error', '{{ trans('messages.notify.error') }}', '{{ trans('messages.automation.email.template.no_file_select') }}');
                
                return;
            }
        
            var url = $(this).attr('action');
            var fd = new FormData($(this)[0]);
            
            popup.loading();
            
            $.ajax({
                url: url,  
                type: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                globalError: false,
                success: function(data) {
                    popup.load('{{ action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}');

                    builderSelectPopup.load('{{ action('Automation2Controller@templateBuilderSelect', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}');

                    // notify
                    notify(data.status, '{{ trans('messages.notify.success') }}', data.message);
                },
                error: function(res) {
                    // notify
                    var dia = new Dialog('alert', {
                        title: '{{ trans('messages.notify.error') }}',
                        message: res.responseText,
                        ok: function() {
                            popup.load();
                        }   
                    });
                }                
            });
        });
        
        $('#templateFile').change(function(e) {
            $('[for="templateFile"]').html($(this).val());
        });
    </script>
@endsection