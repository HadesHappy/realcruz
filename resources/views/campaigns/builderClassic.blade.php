@extends('layouts.core.frontend_dark')

@section('title', trans('messages.edit_template'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <script src="{{ URL::asset('core/js/UrlAutoFill.js') }}"></script>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-1">
            <h4 class="my-0 me-2 menu-title">{{ $campaign->name }}</h4>
            <i class="material-symbols-rounded">alarm</i>
        </div>
    </li>
@endsection

@section('menu_right')
    <li class="nav-item d-flex align-items-center">
        <a  href="javascript:;"
            onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()"
            class="nav-link py-3 lvl-1 d-flex align-items-center">
            <i class="material-symbols-rounded me-2">arrow_back</i>
            <span>{{ trans('messages.go_back') }}</span>
        </a>
    </li>
    <li class="nav-item d-flex align-items-center">
        <a href="{{ action('CampaignController@builderPlainEdit', [
            'uid' => $campaign->uid
        ]) }}"
            class="nav-link py-3 lvl-1">
            <span>{{ trans('messages.campaign.plain_text_editor') }}</span>
        </a>
    </li>
    <li class="d-flex align-items-center px-3">
        <button class="btn btn-primary" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
    </li>
    <li>
        <a href="javascript:;"
            onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()"
            class="nav-link close-button action black-close-button">
            <i class="material-symbols-rounded">close</i>
        </a>
    </li>
@endsection

@section('content')
    {{-- <header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="height: 56px;">
			<a class="navbar-brand left-logo mr-0" href="#">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
					<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
				@else
					<img height="22" src="{{ URL::asset('images/logo_light_blue.svg') }}" alt="">
				@endif
			</a>
			<div class="d-inline-block d-flex mr-auto align-items-center">
                <a style="" href="javascript:;" onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()" class="action black-back-button mr-3">
					<i class="material-symbols-rounded">arrow_back</i>
				</a>
                <h1 class="">{{ $campaign->name }}</h1>
				<i class="material-symbols-rounded automation-head-icon ml-2">web</i>
			</div>
			<div class="automation-top-menu">
                <a class="action mr-4" href="{{ action('CampaignController@builderPlainEdit', [
                    'uid' => $campaign->uid
                ]) }}">
                    {{ trans('messages.campaign.plain_text_editor') }}
                </a>
				<button class="btn btn-primary" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
			</div>
            <a href="javascript:;" onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()"
                class="action black-close-button ml-2" style="margin-right: -15px">
                <i class="material-symbols-rounded">close</i>
            </a>
		</nav>
	</header> --}}
    <form id="classic-builder-form" action="{{ action('CampaignController@builderClassic', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0 form-groups-bottom-0">
            <div class="col-md-9 pl-0 pb-0 pr-0 form-group-mb-0">
                <div class="loading classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
                @include('helpers.form_control', ['type' => 'textarea',
                    'class' => 'template-editor',
                    'name' => 'html',
                    'label' => '',
                    'value' => $campaign->template->content,
                    'rules' => [],
                    'help_class' => 'campaign'
                ])             
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                <p class="mb-1">{!! trans('messages.campaign.preheader.intro') !!}</p>
                @include('helpers.form_control', [
                    'required' => true,
                    'type' => 'textarea',
                    'label' => '',
                    'name' => 'preheader',
                    'value' => $campaign->preheader,
                    // 'rules' => ['preheader' => 'required'],
                ])
                <hr>
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($campaign->defaultMailList)])
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

        $('#classic-builder-form').submit(function(e) {
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
                                removeMaskLoading();
                                
                                // notify
                                parent.notify('error', '{{ trans('messages.notify.error') }}', res.responseText);
                            }
                        },
                        success: function (response) {
                            removeMaskLoading();

                            if (typeof(parent.builderSelectPopup) != 'undefined') {
                                parent.builderSelectPopup.hide();
                            }

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

                        @foreach(Acelle\Model\Template::tags($campaign->defaultMailList) as $tag)
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
