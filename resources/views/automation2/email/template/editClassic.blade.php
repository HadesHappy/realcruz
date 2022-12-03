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
        <a href="{{ action('Automation2Controller@templateEditPlain', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}"
            class="nav-link py-3 lvl-1">
            <span>{{ trans('messages.campaign.plain_text_editor') }}</span>
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
    <form id="classic-builder-form" action="{{ action('Automation2Controller@templateEditClassic', [
        'uid' => $automation->uid,
        'email_uid' => $email->uid,
    ]) }}" method="POST" class="ajax_upload_form builder-classic-form form-validate-jquery">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0">
            <div class="col-md-9 pl-0 pb-0 pr-0 form-group-mb-0">
                <div class="loading classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
                @include('helpers.form_control', [
                    'class' => 'template-editor',
                    'required' => true,
                    'label' => '',
                    'type' => 'textarea',
                    'name' => 'content',
                    'value' => $email->getTemplateContent(),
                    'rules' => ['content' => 'required']
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

                if($('textarea[name="html"]').length || $('textarea[name="content"]').length) {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
                } else {
                    speechSynthesis;
                    $('textarea[name="plain"]').val($('textarea[name="plain"]').val()+tag);
                }
            });
        });
    </script>

    <script>
        var urlFill = new UrlAutoFill([
            {value: '{UNSUBSCRIBE_URL}', text: '{{ trans('messages.editor.unsubscribe_text') }}'},
            {value: '{UPDATE_PROFILE_URL}', text: '{{ trans('messages.editor.update_profile_text') }}'},
            {value: '{WEB_VIEW_URL}', text: '{{ trans('messages.editor.click_view_web_version') }}'}
        ]);

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

        var editor;
        $(document).ready(function() {
            editor = tinymce.init({
                language: '{{ Auth::user()->customer->getLanguageCode() }}',
                selector: '.template-editor',
                directionality: "{{ Auth::user()->customer->text_direction }}",
                height: parent.$('.full-iframe-popup').height()-53,
                convert_urls: false,
                remove_script_host: false,
                forced_root_block: "",
                plugins: 'fullpage print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                imagetools_cors_hosts: ['picsum.photos'],
                menubar: 'file edit view insert format tools table help',
                toolbar: [
                    'ltr rtl | acelletags | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify',
                    'outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl'
                ],
                toolbar_location: 'top',
                menubar: true,
                statusbar: false,
                toolbar_sticky: true,
                valid_elements : '*[*],meta[*]',
                valid_children: '+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div]',
                extended_valid_elements : "meta[*]",
                valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
                external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager2/",
                filemanager_title:"Responsive Filemanager" ,
                external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager2/plugin.min.js"},
                setup: function (editor) {
                    
                    /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                    /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                    editor.ui.registry.addMenuButton('acelletags', {
                        text: '{{ trans('messages.editor.insert_tag') }}',
                        fetch: function (callback) {
                        var items = [];

                        // Unsubscribe link
                        items.push({
                            type: 'menuitem',
                            text: 'UNSUBSCRIBE_LINK',
                            onAction: function (_) {
                                editor.insertContent('<a href="{UNSUBSCRIBE_URL}">{{ trans('messages.editor.unsubscribe_text') }}</a>');
                            }
                        });                        

                        // web view url
                        items.push({
                            type: 'menuitem',
                            text: 'WEB_VIEW_LINK',
                            onAction: function (_) {
                                editor.insertContent('<a href="{WEB_VIEW_URL}">{{ trans('messages.editor.click_view_web_version') }}</a>');
                            }
                        });

                        @foreach(Acelle\Model\Template::tags($automation->mailList) as $tag)
                            items.push({
                                type: 'menuitem',
                                text: '{{ $tag["name"] }}',
                                onAction: function (_) {
                                    editor.insertContent('{{ "{".$tag["name"]."}" }}');
                                }
                            });
                        @endforeach

                        callback(items);
                        }
                    });

                    editor.on('init', function(e) {
                        $('.classic-loader').remove();
                    });
                }
            });
        });
    </script>
@endsection

