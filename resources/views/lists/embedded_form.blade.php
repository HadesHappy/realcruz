@extends('layouts.core.frontend')

@section('title', $list->name)

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/prismjs/prism.css') }}">
    <script type="text/javascript" src="{{ URL::asset('core/prismjs/prism.js') }}"></script>


    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>

@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-semibold text-primary my-4">{{ trans('messages.Embedded_form') }}</h2>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-semibold">{{ trans('messages.options') }}</h4>
            <form id="EmbeddedForm" action="{{ action("MailListController@embeddedForm", $list->uid) }}" class="embedded-options-form">
                {{ csrf_field() }}
                <div class="d-flex justify-content-space-between" style="width:100%;justify-content: space-between">
                    <div class="me-4">
                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'options[form_title]',
                                'label' => trans('messages.form_title'),
                                'value' => $list->getEmbeddedFormOption('form_title'),
                                'help_class' => 'list'
                        ])

                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'options[redirect_url]',
                                'label' => trans('messages.list.embedded_form.redirect_url'),
                                'value' => $list->getEmbeddedFormOption('redirect_url'),
                                'help_class' => 'list',
                                'placeholder' => trans('messages.list.redirect_url.placeholder'),
                        ])
                    </div>
                    <div class="me-4">
                        <div class="form-group">
                            <label>{!! trans('messages.show_only_required_fields', ["link" => action('FieldController@index', $list->uid)]) !!}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'options[only_required_fields]',
                                    'label' => '',
                                    'value' => $list->getEmbeddedFormOption('only_required_fields'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('messages.stylesheet_included') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'options[stylesheet]',
                                    'label' => '',
                                    'value' => $list->getEmbeddedFormOption('stylesheet'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="me-4">
                        <div class="form-group">
                            <label>{{ trans('messages.include_javascript') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'options[javascript]',
                                    'label' => '',
                                    'value' => $list->getEmbeddedFormOption('javascript'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('messages.embeded_form.show_invisible') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'options[show_invisible]',
                                    'label' => '',
                                    'value' => $list->getEmbeddedFormOption('show_invisible'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="" style="width: 440px">

                                @include('helpers.form_control', ['type' => 'textarea',
                                    'name' => 'options[custom_css]',
                                    'class' => 'height-100 text-small',
                                    'label' => trans('messages.custom_css'),
                                    'value' => $list->getEmbeddedFormOption('custom_css'),
                                    'help_class' => 'list'
                                ])

                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr />
    <div class="embedded-form-result">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex">
                    <h4 class="text-semibold me-auto">{{ trans('messages.Copy_paste_onto_your_site') }}</h4>
                    <div>
                        <a href="javascript:;" onclick="copyToClipboard(htmlDecode($('.main-code').html()));
                        notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.embedded_form_code.copied') }}');" class="btn btn-sm btn-light copy-clipboard">
                            <span class="material-symbols-rounded">
                                content_copy
                                </span> {{ trans('messages.copy') }}</a>
                    </div>
                </div>
                    
                    <pre class="language-markup content-group embedded-code"><code></code></pre>
                    <code style="height: 400px" class="form-control main-code hide">@include("lists._embedded_form_content")</code>
            </div>
            <div class="col-md-6">
                <h4 class="text-semibold">{{ trans('messages.preview') }}</h4>
                <iframe class="embedded_form" src="{{ action("MailListController@embeddedFormFrame", $list->uid) }}"></iframe>
            </div>
        </div>
    </div>

    

    <script>
        var EmbeddedForm = {
            formatCopyCode: function() {
                var bio_text = $(".main-code").html();
                bio_text = bio_text.replace(/\</g, '&lt;');
                bio_text = bio_text.replace(/script_tmp/g, 'script');
                bio_text = bio_text.replace(/\t/g, '');
                bio_text = bio_text.replace(/\n/g, '');
                bio_text = bio_text.replace(/\s+/g, ' ');
                bio_text = bio_text.replace(/\>\s*&lt;/g, "&gt;\n&lt;");
                bio_text = bio_text.replace(/\s+\{\s+/g, "{");
                $("code").html(bio_text);
                
                // Hightlight code
                Prism.highlightAll();
            },

            save: function() {
                var form = $('#EmbeddedForm');
                var url = form.attr('action');
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: url,
                    data: data
                })
                .done(function( msg ) {
                    var html = $("<div>").html(msg).find(".embedded-form-result").html();
                    $(".embedded-form-result").html(html);
                    
                    EmbeddedForm.formatCopyCode();
                });
            },
        };
        
        $(function() {
            EmbeddedForm.formatCopyCode();

            //
            $(document).on("change keyup", ".embedded-options-form :input", function() {
                var url = $(this).parents("form").attr("action");

                EmbeddedForm.save();
            });
        });
    </script>
@endsection
