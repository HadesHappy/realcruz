<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ trans('messages.form.builder') }} - {{ $form->name }} - {{ \Acelle\Model\Setting::get("site_name") }}</title>
	
	@include('layouts.core._head')
	@include('layouts.core._script_vars')

    <link rel="stylesheet" href="{{ URL::asset('core/slider/bootstrap-slider.min.css') }}" />
    <script type="text/javascript" src="{{ URL::asset('core/slider/bootstrap-slider.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

    <link type="text/css" rel="stylesheet" href="{{ URL::asset('core/css/form-builder.css') }}" />
</head>

<body class="" style="overflow: hidden">

	<div class="acelle-top-menu">
        <nav class="navbar fixed-top navbar-light bg-light shadow-sm px-4 py-0">
            <span class="navbar-brand d-flex align-items-center pl-0">
                <span class="material-symbols-rounded mr-2">
                    dashboard
                </span>
                <div class="d-flex align-items-center">
                    <div>
                        {{ trans('messages.form.builder') }} -
                        {{ $form->name }}
                    </div>
                    <div class="ms-3">
                        <span class="label label-flat bg-{{ $form->status }}">{{ trans('messages.form.status.' . $form->status) }}</span>
                    </div>
                </div>
                    
            </span>
            <div class="ms-auto">
                <button class="me-2 builder-save-button btn btn-secondary" href="{{ action('FormController@index') }}">
                    <div class="d-flex align-items-center">
                        <span class="material-symbols-rounded me-2">
                            task_alt
                            </span>
                            <span>{{ trans('messages.form.builder.save') }}</span>
                    </div>
                </button>
            </div>
            <span class="pe-2 text-muted2"> | </span>
            <div class="me-4">
                @if (Auth::user()->customer->can('publish', $form))
                    <a href="{{ action('FormController@publish', [
                        'uids' => [$form->uid],
                    ]) }}" class="me-1 builder-publish-button btn btn-default" href="{{ action('FormController@index') }}">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-2">
                                task_alt
                                </span>
                                <span>{{ trans('messages.form.publish') }}</span>
                        </div>
                            
                    </a>                    
                @endif
                @if (Auth::user()->customer->can('unpublish', $form))
                    <a href="{{ action('FormController@unpublish', [
                        'uids' => [$form->uid],
                    ]) }}" class="me-1 builder-unpublish-button btn btn-default" href="{{ action('FormController@index') }}">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-2">
                                do_disturb_on
                                </span>
                                <span>{{ trans('messages.form.unpublish') }}</span>
                        </div>
                            
                    </a>
                @endif
                <a href="javascript:;" class="me-2 builder-view-button btn btn-default">
                    <div class="d-flex align-items-center">
                        <span class="material-symbols-rounded me-2">
                            featured_video
                            </span>
                            <span>{{ trans('messages.form.open_popup') }}</span>
                    </div>
                </a> 
            </div>
            
            
            
            <div>
                
                <a class="fs-4 builder-exit-button" href="{{ action('FormController@index') }}"><span class="material-symbols-rounded">
                    close
                    </span></a>
            </div>
            <script>
                $(function() {
                    $('.builder-publish-button, .builder-unpublish-button').on('click', function(e) {
                        e.preventDefault();
                        var but = $(this);

                        addButtonMask(but);
                        // save
                        FormsEdit.getFormsBuilder().save(function() {
                            
                            // do publish/unpublish
                            FormsEdit.topAction(but.attr('href'));
                        })                        
                    });

                    // view
                    $('.builder-view-button').on('click', function() {
                        FormsEdit.openPopup();
                    });
                    
                    // save
                    $('.builder-save-button').on('click', function() {
                        var button = $(this);

                        addButtonMask(button);
                        FormsEdit.getFormsBuilder().save(function() {
                            removeButtonMask(button);
                        });
                    });

                    // update overlay
                    FormsEdit.settings = {!! json_encode($form->getMetadata()) !!};
                });
            </script>
        </nav>
    </div>
    <div class="">
        <iframe id="FormsEditIframe" scrolling="no" style="width: 100%;
        height: calc(100vh - 53px);border:none;overflow:hidden" src="{{ action('FormController@builder', [
            'uid' => $form->uid,
        ]) }}"></iframe>
    </div>

    <div class="styles-settings shadow-sm">
        <div class="styles-sections">
            <div class="styles-section form-layouts">
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-symbols-rounded" style="line-height:30px">
                        dashboard
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4">
                    <div class="d-flex align-items-center styles-container-heading shadow">
                        <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                            <span class="material-symbols-rounded" style="line-height:30px">
                                keyboard_backspace
                            </span>
                        </a>
                        <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.layouts') }}</h6>
                    </div>  
                    <hr>
                    <div class="">
                        @foreach ($templates->take(5) as $template)
                            <a href="{{ action('FormController@changeTemplate', [
                                'uid' => $form->uid,
                                'template_uid' => $template->uid,
                            ]) }}" class="style-item d-block rounded-3 overflow-hidden mb-4 shadow-sm">
                                <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                            </a>
                        @endforeach
                    </div>
                </div>  
            </div>
            <div class="styles-section form-theme">
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-symbols-rounded" style="line-height:30px">
                        web
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4">    
                    <div class="d-flex align-items-center styles-container-heading shadow-sm">
                        <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                            <span class="material-symbols-rounded" style="line-height:30px">
                                keyboard_backspace
                            </span>
                        </a>
                        <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.themes') }}</h6>
                    </div>                    
                        
                    <hr>
                    <div class="">
                        @foreach ($templates->skip(5) as $template)
                            <a href="{{ action('FormController@changeTemplate', [
                                'uid' => $form->uid,
                                'template_uid' => $template->uid,
                            ]) }}" class="style-item d-block rounded-3 overflow-hidden mb-4 shadow-sm">
                                <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                            </a>
                        @endforeach
                    </div>
                </div>  
            </div>
            <div class="styles-section form-theme">
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-symbols-rounded" style="line-height:30px">
                        tune
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4" style="
                    overflow-x:hidden!important; height:auto;
                    max-height: calc(100vh - 100px);
                ">    
                    <div class="d-flex align-items-center styles-container-heading shadow-sm">
                        <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                            <span class="material-symbols-rounded" style="line-height:30px">
                                keyboard_backspace
                            </span>
                        </a>
                        <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.form_settings') }}</h6>
                    </div>                    
                        
                    <hr>
                    <div class="" style="width:201px">
                        <form id="FormDisplaySetting" action="{{ action('FormController@settings', [
                            'uid' => $form->uid
                        ]) }}" method="POST">
                            {{ csrf_field() }}

                            <div class="mb-4">
                                @include('helpers.form_control', [
                                    'type' => 'text',
                                    'name' => 'name',
                                    'label' => trans('messages.name'),
                                    'value' => $form->name,
                                    'required' => true,
                                ])
                            </div>

                            <div class="mb-4">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'mail_list_uid',
                                    'label' => trans('messages.list'),
                                    'value' => $form->mailList->uid,
                                    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                                    'rules' => [],
                                ])
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">{{ trans('messages.form.overlay_opacity') }}</label>
                                <input id="opacityOverlay" name="overlay_opacity"
                                    type="text"
                                    data-slider-min="0"
                                    data-slider-max="100"
                                    data-slider-step="1"
                                    data-slider-value="{{ $form->getMetadata('overlay_opacity') ? $form->getMetadata('overlay_opacity') : '50'}}"
                                />
                                <script>
                                    $(function() {
                                        // With JQuery
                                        $('#opacityOverlay').slider();
                                    });
                                </script>
                            </div>

                            <div class="mb-3 form-group-mb-0">
                                <label class="mb-2">{{ trans('messages.form.display') }}</label>
                                <p class="small text-muted">{{ trans('messages.form.display.desc') }}</p>
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'display',
                                    'label' => '',
                                    'value' => $form->getMetadata('display'),
                                    'options' => [
                                        ["value" => "immediately", "text" => trans('messages.form.display.immediately')],
                                        ["value" => "first_visit", "text" => trans('messages.form.display.first_visit')],
                                        ["value" => "wait", "text" => trans('messages.form.display.wait')],
                                        ["value" => "click", "text" => trans('messages.form.display.click')],
                                    ],
                                ])
                            </div>
                            
                            <div class="display-select-page-load mb-3">
                                <div class="">
                                    <p class="small text-muted mb-1">{{ trans('messages.form.display.immediately.desc') }}</p>
                                </div>
                            </div>

                            <div class="display-select-first_visit mb-3">
                                <div class="">
                                    <p class="small text-muted mb-1">{{ trans('messages.form.display.first_visit.desc') }}</p>
                                </div>
                            </div>
                            
                            <div class="display-select-wait mb-3">
                                <div class="">
                                    <p class="small text-muted mb-1">{{ trans('messages.form.display.wait_time.desc') }}</p>
                                    <label class="mb-2">{{ trans('messages.form.display.wait_time') }}</label>                                    
                                    @include('helpers.form_control.number', [
                                        'name' => 'wait_time',
                                        'value' => $form->getMetadata('wait_time') ? $form->getMetadata('wait_time') : '5',
                                        'attributes' => [
                                            'class' => 'numeric',
                                            'min' => '1',
                                            'required' => 'required',
                                        ],
                                    ])
                                </div>
                            </div>

                            <div class="display-select-element mb-3">
                                <p class="small text-muted mb-1">{{ trans('messages.form.display.element_id.desc') }}</p>
                                <label class="mb-2">{{ trans('messages.form.display.element_id') }}</label>                                
                                @include('helpers.form_control.text', [
                                    'name' => 'element_id',
                                    'value' => $form->getMetadata('element_id') ? $form->getMetadata('element_id') : '',
                                    'attributes' => [
                                        'required' => 'required',
                                    ],
                                ])
                            </div>

                            {{-- <div class="mb-3">
                                @include('helpers.form_control', [
                                    'type' => 'checkbox2',
                                    'name' => 'use_captcha',
                                    'label' => trans('messages.form.use_captcha'),
                                    'value' => $form->getMetadata('use_captcha'),
                                    'options' => ['no', 'yes'],
                                ])
                            </div> --}}

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary display-settings-save">{{ trans('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
            <div class="styles-section form-theme">
                <a href="javascript:;" class="styles-menu-button preview-popup px-2 py-1">
                    <span class="material-symbols-rounded xtooltip" title="{{ trans('messages.preview') }}" style="line-height:30px">
                        visibility
                    </span>
                </a> 
            </div>
        </div>
    </div>

    <script>
        @include('forms.frontend.popupJs')
    </script>

	<script>
        var FormsEdit = {
            styleManager: null,
            displayOption: null,
            settings: null,

            // STYLE MANAGER
            getSettingsManager: function() {
                var _this = this;

                if (_this.styleManager == null) {
                    _this.styleManager = new GroupManager();

                    $('.styles-settings .styles-section').each(function() {
                        _this.styleManager.add({
                            box: $(this),
                            button: $(this).find('.styles-toggle'),
                            container: $(this).find('.styles-container'),
                            back: $(this).find('.styles-back')
                        });
                    });

                    _this.styleManager.bind(function(group, others) {
                        // show
                        group.show = function() {
                            // hide others
                            others.forEach(function(other) {
                                other.hide();
                            });

                            group.container.addClass('show');
                            $('.styles-settings').addClass('open');

                            group.container.scrollTop(0);
                        };

                        // hide
                        group.hide = function() {
                            group.container.removeClass('show');
                            $('.styles-settings').removeClass('open');
                        }

                        group.button.on('click', function() {
                            // toggle container
                            group.show();                       
                        });

                        group.back.on('click', function() {
                            // toggle container
                            group.hide();                       
                        });
                    });
                }
                return this.styleManager;
            },

            // STYLE MANAGER
            getDisplayManager: function() {
                var _this = this;

                if (_this.displayManager == null) {
                    _this.displayManager = new GroupManager();

                    _this.displayManager.add({
                        box: $('.display-select-page-load'),
                        value: 'immediately',
                        selectedValue: function() {
                            return $('[name="display"]').val();
                        }
                    });
                    
                    _this.displayManager.add({
                        box: $('.display-select-first_visit'),
                        value: 'first_visit',
                        selectedValue: function() {
                            return $('[name="display"]').val();
                        }
                    });

                    _this.displayManager.add({
                        box: $('.display-select-element'),
                        value: 'click',
                        selectedValue: function() {
                            return $('[name="display"]').val();
                        }
                    });

                    _this.displayManager.add({
                        box: $('.display-select-wait'),
                        value: 'wait',
                        selectedValue: function() {
                            return $('[name="display"]').val();
                        }
                    });

                    _this.displayManager.bind(function(group) {
                        group.check = function() {
                            if (group.selectedValue() == group.value) {
                                group.box.show();
                            } else {
                                group.box.hide();
                            }
                        }

                        $('[name="display"]').on('change', function() {
                            group.check();
                        });

                        group.check();
                    });                        
                }
                return this.styleManager;
            },

            saveSettings: function() {
                var _this = this;
                var form = $('#FormDisplaySetting');
                var url = form.attr('action');
                var data = form.serialize();

                addMaskLoading();

                if (form.valid()) {
                    // copy
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data
                    }).done(function(response) {
                        notify({
                            message: response.message
                        });

                        removeMaskLoading();

                        _this.hideSidebar();

                        _this.refreshAll();
                    });
                }
            },

            getEditor: function() {
                return document.getElementById('FormsEditIframe').contentWindow.editor;
            },

            getFormsBuilder: function() {
                return document.getElementById('FormsEditIframe').contentWindow.FormsBuilder;
            },

            loadUrls: function(urls, callback) {
                var _this = this;

                _this.getEditor().saveUrl = urls.saveUrl;
                _this.getEditor().uploadAssetUrl = urls.uploadAssetUrl;
                _this.getEditor().url = urls.url;

                // save current form container
                _this.getEditor().cleanUpContent();

                _this.getEditor().loadUrl(_this.getEditor().url, function() {
                    if (typeof(callback) != 'undefined') {
                        callback();
                    }
                    _this.getEditor().adjustIframeSize();
                });
            },

            connectPopup: null,
            getConnectPopup: function() {
                if (this.connectPopup == null) {
                    this.connectPopup = new Popup({
                        url: '{{ action('FormController@connect', [
                            'uid' => $form->uid,
                        ]) }}'
                    });
                }
                return this.connectPopup;
            },

            openPopup: function() {
                popup = new AFormPopup({
                    url: '{{ action('FormController@frontendContent', [
                        'uid' => $form->uid,
                    ]) }}',
                    overlayOpacity: this.settings.overlay_opacity/100
                });

                popup.load();
            },

            preview: function() {
                var _this = this;

                $.ajax({
                    url: "{{ action('FormController@preview', [
                            'uid' => $form->uid,
                    ]) }}",
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        content: _this.getFormsBuilder().getEditor().getContent()
                    }
                })
                .done(function(res) {
                    popup = new AFormPopup({
                        url: '{{ action('FormController@frontendContent', [
                            'uid' => $form->uid,
                            'preview' => true,
                        ]) }}',
                        overlayOpacity: _this.settings.overlay_opacity/100
                    });

                    popup.load();
                });
                
            },

            refreshNavbar: function(callback) {
                $.ajax({
                    url: "",
                    method: 'GET'
                })
                .done(function(res) {
                    var html = $('<div>').html(res).find('.navbar').html();
                    $('.navbar').html(html);

                    if (typeof(callback) != 'undefined') {
                        callback();
                    }
                })
            },

            refreshAll: function(callback) {
                var _this = this;

                _this.refreshNavbar(function() {
                    _this.getFormsBuilder().refreshAddressBar(callback);
                });
            },

            topAction: function(url) {
                addMaskLoading();

                // publish
                new Link({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    before: function() {
                        
                    },
                    done: function(response) {
                        notify({
                            type: 'success',
                            message: response.message,
                        });

                        FormsEdit.refreshAll(function() {
                            removeMaskLoading();
                        });
                    }
                });
            },

            hideSidebar: function() {
                this.getSettingsManager().groups.forEach(function(group) {
                    group.hide();
                });
            },

            canNotViewUnpublishedForm: function() {
                new Dialog('alert', {
                    message: `{{ trans('messages.form.view_in_site_but_is_not_published') }}`
                });
            },

            changeTemplate: function(url) {
                var _this = this;

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN
                    }
                }).done(function(response) {
                    _this.loadUrls(response, function() {
                        _this.getFormsBuilder().openFormTab();
                    });
                    _this.hideSidebar();
                });
            }
        }
        
        $(function() {
            FormsEdit.getSettingsManager();
            FormsEdit.getDisplayManager();
            

            // remove loadding effect
            $('.lds-dual-ring').remove();

            $('.style-item').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                FormsEdit.changeTemplate(url);
            });

            // display setting save
            $('.display-settings-save').on('click', function() {
                FormsEdit.saveSettings();
            });


            // preview
            $('.preview-popup').on('click', function() {
                FormsEdit.preview();
            });

            // hide style
            $('.navbar').on('click', function() {
                FormsEdit.hideSidebar();
            });
        })
    </script>
</body>
</html>
