@extends('layouts.core.frontend_dark')

@section('title', trans('messages.edit_template'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <script src="{{ URL::asset('core/js/UrlAutoFill.js') }}"></script>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-1 lvl-1">
            <h4 class="my-0 me-2 menu-title">{{ $automation->name }}</h4>
            <i class="material-symbols-rounded">alarm</i>
        </div>
    </li>
@endsection

@section('menu_right')
    <li class="nav-item d-flex align-items-center">
        <a  href="javascript:;"
            class="nav-link py-3 lvl-1 close-button">
            <i class="material-symbols-rounded me-2">arrow_back</i>
            <span>{{ trans('messages.go_back') }}</span>
        </a>
    </li>
    <li class="nav-item d-flex align-items-center">
        <a href="{{ action('Automation2Controller@templateEditClassic', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}"
            class="nav-link py-3 lvl-1">
            <span>{{ trans('messages.campaign.html_editor') }}</span>
        </a>
    </li>
    <li class="d-flex align-items-center px-3">
        <button class="btn btn-primary" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
    </li>
    <li>
        <a href="javascript;"
            class="nav-link close-button action black-close-button">
            <i class="material-symbols-rounded">close</i>
        </a>
    </li>
@endsection

@section('content')
    <form id="classic-builder-form" action="{{ action('Automation2Controller@templateEditPlain', [
        'uid' => $automation->uid,
        'email_uid' => $email->uid,
    ]) }}" method="POST" class="ajax_upload_form builder-classic-form form-validate-jquery">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0">
            <div class="col-md-9 pl-0 pb-0 pr-0 form-group-mb-0">
                @include('helpers.form_control', [
                    'class' => 'campaign-plain-text',
                    'required' => true,
                    'label' => '',
                    'type' => 'textarea',
                    'name' => 'plain',
                    'value' => $email->plain,
                    'rules' => ['plain' => 'required']
                ])        
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($automation->mailList)])
            </div>            
        </div>   
    <form>

    <script>
        $(function() {
            // Click to insert tag
            $(document).on("click", ".insert_tag_button", function() {
                var tag = $(this).attr("data-tag-name");
                insertAtCursor($('textarea[name="plain"]')[0], tag);
            });
        });
    </script>

    <script>
        $('.close-button').click(function() {
            parent.$('.full-iframe-popup').remove();
            popup.load();
        });

        $('.builder-classic-form').submit(function(e) {
            e.preventDefault();

            tinymce.triggerSave();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            if ($(this).valid()) {
                // open builder effects
                addMaskLoading("{{ trans('messages.automation.template.saving') }}", function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        statusCode: {
                            // validate error
                            400: function (res) {
                                console.log('Something went wrong!');
                            }
                        },
                        success: function (response) {
                            removeMaskLoading();

                            // notify
                            parent.notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                        }
                    });
                });         
            }     
        });

        $('.sidebar').css('height', parent.$('.full-iframe-popup').height()-53);
        $('[name=plain]').css('height', parent.$('.full-iframe-popup').height()-53);
    </script>
@endsection