<!doctype html>
<html>
    <head>
        <title>{{ trans('messages.form.builder') }} - {{ $form->name }}</title>
        <meta charset="utf-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        @include('layouts.core._favicon')
        
        <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"> 

        <!-- Google icon -->
        <link href="{{ URL::asset('core/css/google-font-icon.css') }}" rel="stylesheet">

        <script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>

        <link type="text/css" rel="stylesheet" href="{{ URL::asset('core/css/form-builder.css') }}" />

        <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

        @include('helpers._builder_form')

        <script>
            var CSRF_TOKEN = "{{ csrf_token() }}";
            var editor;
            var formFields = {!! json_encode($formFields) !!};
            
            $( document ).ready(function() {
                editor = new Editor({
                    strict: true,
                    showHelp: false,
                    showInlineToolbar: false,

                    formFields: formFields,
                    
                    root: '{{ URL::asset('builder') }}/',                
                    lang: {!! json_encode(language()->getBuilderLang()) !!},
                    url: '{{ action('FormController@builderContent', [
                        'uid' => $form->uid,
                    ]) }}',
                    saveUrl: '{{ action('TemplateController@builderEdit', $form->template->uid) }}',
                    saveMethod: 'POST',
                    uploadAssetUrl: '{{ action('TemplateController@uploadTemplateAssets', $form->template->uid) }}',
                    uploadAssetMethod: 'POST',
                    backCallback: function() {
                        
                    },

                    tags: {!! json_encode($form->getBuilderTags()) !!},

                    canvas: '#formCanvas',
                    sidePanel: '#formWidgetPanel',

                    filemanager: '{{ URL::asset('filemanager2/dialog.php') }}',
                    logo: '{{ \Acelle\Model\Setting::get('site_logo_small') ? action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) : URL::asset('images/logo_light_blue.svg') }}',
                    backgrounds: [
                        '{{ url('/images/backgrounds/images1.jpg') }}',
                        '{{ url('/images/backgrounds/images2.jpg') }}',
                        '{{ url('/images/backgrounds/images3.jpg') }}',
                        '{{ url('/images/backgrounds/images4.png') }}',
                        '{{ url('/images/backgrounds/images5.jpg') }}',
                        '{{ url('/images/backgrounds/images6.jpg') }}',
                        '{{ url('/images/backgrounds/images9.jpg') }}',
                        '{{ url('/images/backgrounds/images11.jpg') }}',
                        '{{ url('/images/backgrounds/images12.jpg') }}',
                        '{{ url('/images/backgrounds/images13.jpg') }}',
                        '{{ url('/images/backgrounds/images14.jpg') }}',
                        '{{ url('/images/backgrounds/images15.jpg') }}',
                        '{{ url('/images/backgrounds/images16.jpg') }}',
                        '{{ url('/images/backgrounds/images17.png') }}'
                    ],
                    loaded: function() {
                        if (!editor.getIframeContent().find('#formContainer').children().length) {
                            FormsBuilder.loadWidgets();

                            // first save
                            editor.save();
                        }

                        // js auto height iframe
                        editor.adjustIframeSize();

                        // hide styles
                        $("#builder_iframe").contents().click(function(e) {
                            FormsBuilder.getFormsEdit().hideSidebar();
                        });

                        //
                        editor.addCustomCss('{{ URL::asset('core/css/form-builder-frame.css') }}');

                        // change banner
                        $("#builder_iframe").contents().find("body").on('click', '.banner-container:not(.bg-changed)', function(e) {
                            setTimeout(function() {
                                $('.change:visible').click();
                            }, 300);
                        });

                        // overwrite notify method
                        editor.notificationArea = function(content) {
                            parent.notify({
                                type: 'success',
                                message: content
                            });
                        }

                        // scrolling
                        $('.content-left').on('scroll', function() {
                            FormsBuilder.togglePageTabs();
                        });

                        // add close button
                        $('.content-background').prepend(`<span class="material-symbols-rounded popup-close-button">close</span>`);
                    }
                });
            
                editor.init();
            });
        </script>
    </head>
    <body>        
        <div class="">
            <div>
                <div class="browser-backdrop">
                    <div class="browser-frame">
                        <div class="browser-head d-flex align-items-center shadow">
                            <div>
                                <div class="control-dots d-flex align-items-center">
                                    <div class="control-dot"></div>
                                    <div class="control-dot orange"></div>
                                    <div class="control-dot green"></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center px-5" style="width: 100%">
                                <div class="browser-address rounded d-flex align-items-center shadow-sm" value="" style="">
                                    <div class="d-flex align-items-center" style="width: 100%">
                                        @if($form->getMetadata('website_uid'))
                                            <div class="mr-4" style="width:1%">
                                                <span class="material-symbols-rounded text-success" style="font-size: 20px;transform: translateY(3px)">
                                                    link_on
                                                </span>
                                            </div>
                                            <div class="address-container">
                                                <a class="link-dark"
                                                    target="_blank"  title="{{ $form->getWebsite()->title }}" href="{{ $form->getWebsite()->url }}">
                                                    {{ $form->getWebsite()->url }}
                                                </a>
                                            </div>
                                            <div class="ml-auto">
                                                <a href="javascript:;" class="connect-to-site link-dark">
                                                    <span class="material-symbols-rounded mr-1" style="font-size:11px">
                                                        edit
                                                        </span>{{ trans('messages.form.connect_edit') }}
                                                </a><span class="mx-1"> | </span>
                                                <a target="_blank" href="{{ $form->getWebsite()->url }}"
                                                    class="link-dark  {{ !$form->isPublished() ? 'open-not-published-site-confirm' : '' }}"
                                                >
                                                    <span class="material-symbols-rounded mr-0" style="font-size:11px">
                                                        launch
                                                        </span>
                                                {{ trans('messages.form.view_on_site') }}
                                                    
                                                </a>
                                            </div>
                                        @else
                                            <div class="mr-4" style="width:1%">
                                                <span class="material-symbols-rounded text-warning" style="font-size: 20px;transform: translateY(3px)">
                                                    link_off
                                                </span>
                                            </div>
                                            <div>
                                                {{ trans('messages.form.builder.connect_to_your_site') }}
                                            </div>
                                            <div class="ml-auto">
                                                <a href="javascript:;" class="connect-to-site">{{ trans('messages.form.connect_site') }}</a>
                                            </div>
                                        @endif

                                        <script>
                                            $(function() {
                                                $('.connect-to-site').on('click', function(e) {
                                                    e.preventDefault();
                                
                                                    FormsBuilder.getFormsEdit().getConnectPopup().load();
                                                });

                                                // open site that is not published confirm
                                                $('.open-not-published-site-confirm').on('click', function(e) {
                                                    e.preventDefault();

                                                    FormsBuilder.getFormsEdit().canNotViewUnpublishedForm();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="page-tabs">
                            <div class="page-tabs-outer d-flex rounded shadow">
                                <div class="">
                                    <a href="javascript:;" class="page-tab active" data-section="form">
                                        {{ trans('messages.form.builer.form_tab') }}
                                    </a>
                                </div>
                                <div class="">
                                    <a href="javascript:;" class="page-tab" data-section="message">
                                        {{ trans('messages.form.builer.success_message_tab') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="formCanvas"></div>
                    </div>
                </div>
            </div>
            <div id="formWidgetPanel"></div>
        </div>    
        
        <script>
            var FormsBuilder = {
                getFormsEdit: function() {
                    return parent.FormsEdit;
                },

                refreshAddressBar: function(callback) {
                    $.ajax({
                        url: "",
                        method: 'GET'
                    })
                    .done(function(res) {
                        var html = $('<div>').html(res).find('.browser-address').html();
                        $('.browser-address').html(html);

                        if (typeof(callback) != 'undefined') {
                            callback();
                        }
                    })
                },

                save: function(callback) {
                    this.openFormTab();
                    editor.save(function() {
                        if (typeof(callback) !== 'undefined') {
                            callback();
                        }
                    });
                },

                loadWidgets: function() {
                    // add default widget
                    formFields.forEach(function(field) {
                        if (field.visible == 1 && field.required) {
                            // create widget from field info
                            var className = field.type.charAt(0).toUpperCase() + field.type.slice(1) + 'FieldWidget';
                            var widget = eval('new ' + className + '(field)');
                            // insert widget content to editor
                            editor.getIframeContent().find('#formContainer').append(`
                                <div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px">
                                    <div class="container">
                                        `+widget.getContentHtml()+`
                                    </div>
                                </div>
                            `); 
                        }                   
                    });

                    // Add submit button
                    var widget = new SubmitButtonWidget();
                    // insert widget content to editor
                    editor.getIframeContent().find('#formContainer').append(`
                        <div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px">
                            <div class="container">
                                `+widget.getContentHtml()+`
                            </div>
                        </div>
                    `);

                    editor.adjustIframeSize();
                },

                pageTabManager: null,
                getPageTabManager: function() {
                    var _this = this;

                    if (_this.pageTabManager == null) {
                        _this.pageTabManager = new GroupManager();

                        $('.page-tab').each(function() {
                            var t = $(this);
                            _this.pageTabManager.add({
                                tab: t,
                                value: t.attr('data-section'),
                                section: function() {
                                    return editor.getIframeContent().find(t.attr('data-section') + '-section');
                                }
                            });
                        });

                        _this.pageTabManager.bind(function(group, others) {
                            group.check = function() {
                                group.section().show();
                                group.tab.addClass('active');

                                others.forEach(function(other) {
                                    other.section().hide();
                                    other.tab.removeClass('active');
                                });

                                _this.resetIframeHeight();
                            }

                            group.tab.on('click', function() {
                                group.check();
                            });
                        });
                            
                    }

                    return _this.pageTabManager;
                },

                getEditor: function() {
                    return editor;
                },

                openFormTab: function() {
                    this.getPageTabManager().groups.forEach(function(group) {
                        if (group.value == "form") {
                            group.check();
                        }
                    }); 
                },

                resetIframeHeight: function() {
                    editor.unselect();

                    $('#builder_iframe').css('height', 'auto');
                    editor.adjustIframeSize();
                },

                togglePageTabs: function() {
                    if ($('.content-left').scrollTop() > 50) {
                        $('.page-tabs').addClass('disappeared');
                    } else {
                        $('.page-tabs').removeClass('disappeared');
                    }
                }
            };

            $(function() {
                // hide menu
                $(document).on('click', function() {
                    FormsBuilder.getFormsEdit().hideSidebar();
                });

                // 
                FormsBuilder.getPageTabManager();
            });
        </script>
  </body>
</html>
