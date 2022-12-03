@extends('layouts.core.backend_dark')

@section('title', $language->name)
    
@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/ace.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/theme-twilight.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/mode-php.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/mode-yaml.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/jquery-ace.min.js') }}"></script>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center text-white">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-1">
            <i class="material-symbols-rounded me-2">translate</i>
            <span class="my-0 me-2">{{ trans('messages.language.translation') }}</span>
            <span class="material-symbols-rounded me-2">
                navigate_next
                </span>
            <span class="my-0 me-3">{{ $language->name }}</span>
        </div>
    </li>
@endsection

@section('menu_right')
    <li class="d-flex align-items-center justify-content-center text-white ms-5 ps-4">
        <label class="me-2 text-muted2">{{ trans('messages.language.select_translation_file') }}</label>
        <div class="form-group-mb-0" style="width:200px">
            <form id="changeFileForm" action="{{ action('Admin\LanguageController@translate', [
                'id' => $language->uid,
            ]) }}" method="GET">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'file_id',
                    'value' => $currentFile['id'],
                    'label' => '',
                    'options' => $language->getLanguageFileOptions(),
                ])
            </form>
        </div>
            
    </li>
    <li class="d-flex align-items-center mx-3">
        <button class="btn btn-primary px-4" onclick="$('#languageTranslate').submit()">{{ trans('messages.save') }}</button>
    </li>
    <li>
        <a href="javascript:;" onclick="LanguagesTranslate.close()"
            class="nav-link close-button action black-close-button">
            <i class="material-symbols-rounded">close</i>
        </a>
    </li>
@endsection

@section('content')
    <style>
        .ace_editor {
            width: 100%!important;
            height: calc(100vh - 53px)!important;
        }
    </style>
    
    <form id="languageTranslate" enctype="multipart/form-data" action="" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}

        <input type="hidden" name="file_id" value="{{ $currentFile['id'] }}" />
        
        <div class="tabbable">
            <div class="tab-content">
                <div class="tab-pane active" id="top-tab1">
                    <textarea name="content" class="my-code-messages" rows="20" style="width: 100%">{!! $content !!}</textarea>
                </div>                            
            </div>
        </div>
    <form>
    
    <script>
        var LanguagesTranslate = {
            changed: false,
            currentFileId: '{{ $currentFile['id'] }}',
            url: '{{ action('Admin\LanguageController@translate', ["id" => $language->uid, "file_id" => $currentFile['id']]) }}',
            
            getData: function() {
                return $('#languageTranslate').serialize();
            },

            close: function() {
                if (LanguagesTranslate.changed) {
                    new Dialog('confirm', {
                        message: '{{ trans('messages.language.discard_change') }}',
                        ok: function() {
                            window.location = '{{ action('Admin\LanguageController@index') }}';
                        },
                        cancel: function() {
                            
                        }
                    });
                } else {
                    window.location = '{{ action('Admin\LanguageController@index') }}';
                }
            },
            
            changeFile: function() {
                if (LanguagesTranslate.changed) {
                    new Dialog('confirm', {
                        message: '{{ trans('messages.language.discard_change') }}',
                        ok: function() {
                            $('#changeFileForm').submit();
                        },
                        cancel: function() {
                            $('[name=file_id]').val(LanguagesTranslate.currentFileId).trigger('change.select2');
                        }
                    });
                } else {
                    $('#changeFileForm').submit();
                }
            },

            save: function() {
                addMaskLoading();

                // load from url
                $.ajax({
                    url: this.url,
                    method: 'POST',
                    data: this.getData(),
                    globalError: false,
                }).done(function(response) {
                    notify({
                        type: response.status,
                        message: response.message
                    });
                    LanguagesTranslate.changed = false;
                }).fail(function(jqXHR, textStatus, errorThrown){        
                    var result = JSON.parse(jqXHR.responseText);
                    new Dialog('alert', {
                        title: LANG_ERROR,
                        message: result.message
                    });
                }).always(function() {
                    removeMaskLoading();
                });
            }
        }

        $(function() {
            $('#languageTranslate').on('submit', function(e) {
                e.preventDefault();

                LanguagesTranslate.save();
            })

            $('[name=file_id]').on('change', function(e) {
                e.preventDefault();

                LanguagesTranslate.changeFile();
            });

            $('.my-code-messages').ace({ theme: 'twilight', lang: 'yaml' });
            $('.sub-menu.active').each(function() {
                if ($(this).closest('li.dropdown').length) {
                    $(this).closest('li.dropdown').addClass('active');
                }
            });

            // LanguagesTranslate.editor = $('.my-code-messages').data('ace').editor.ace
            // LanguagesTranslate.editor.getSession().on('change', function() {
            //     LanguagesTranslate.changed = true;
            // });
            // LanguagesTranslate.editor.commands.removeCommand('find');
        });
        
    </script>
    
@endsection