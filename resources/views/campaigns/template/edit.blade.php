<!doctype html>
<html>
  <head>
    <title>{{ trans('messages.campaign.edit_template') }} - {{ $campaign->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('layouts.core._favicon')
    
    <!-- BuilderJS CORE -->
    <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

    <!-- BuilderJS CUSTOM -->
    <link href="{{ URL::asset('core/css/builder-custom.css') }}" rel="stylesheet" type="text/css">
    @include('builder.js.widgets')

    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/select2/css/select2.min.css') }}">
    <script type="text/javascript" src="{{ URL::asset('core/select2/js/select2.min.js') }}"></script>

    <!-- Autofill -->
    <link href="{{ URL::asset('core/css/UrlAutoFill.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ URL::asset('core/js/UrlAutoFill.js') }}"></script>

    @if ($campaign->template->theme)
        @include('builder.themes.' . $campaign->template->theme)
    @endif

    <script>
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var editor;

        var templates = {!! json_encode($templates) !!};

        $( document ).ready(function() {
            editor = new Editor({
                strict: true,
                showHelp: false,
                showInlineToolbar: false,
                emailMode: true,
                lang: {!! json_encode(language()->getBuilderLang()) !!},
                url: '{{ action('CampaignController@templateContent', $campaign->uid) }}',
                backCallback: function() {
                  parent.$('.full-iframe-popup').fadeOut();
                  parent.$('body').removeClass('overflow-hidden');

                  if (typeof(parent.builderSelectPopup) != 'undefined') {
                    parent.builderSelectPopup.hide();
                  }
                },
                uploadAssetUrl: '{{ action('TemplateController@uploadTemplateAssets', $campaign->template->uid) }}',
                uploadAssetMethod: 'POST',
                saveUrl: '{{ action('CampaignController@templateEdit', $campaign->uid) }}',
                saveMethod: 'POST',
                tags: {!! json_encode(Acelle\Model\Template::builderTags((isset($list) ? $list : null))) !!},
                root: '{{ URL::asset('builder') }}/',
                templates: templates,
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
                    '{{ url('/images/backgrounds/images17.png') }}',
                ],
                loaded: function() {
                    var thisEditor = this;

                    // add custom css
                    this.addCustomCss('{{ url('/core/css/builder-edit.css') }}');
                },
                customInlineEdit: function(container) {
                    thisEditor = this;
                    
                  var tinyconfig = {
                      skin: 'oxide-dark',
                      inline: true,
                      menubar: false,
                      force_br_newlines : false,
                      force_p_newlines : false,
                      forced_root_block : '',
                      inline_boundaries: false,
                      relative_urls: false,
                      convert_urls: false,
                      remove_script_host : false,
                      valid_elements : '*[*],meta[*]',
                      valid_children: '+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div]',
                      plugins: 'image link lists autolink',
                      //toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent',
                      toolbar: [
                          // 'undo redo | bold italic underline | fontselect fontsizeselect | link | menuDateButton',
                          // 'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent'
                      ],
                      external_filemanager_path:'{{ url('/') }}'.replace('/index.php','')+"/filemanager2/",
                      filemanager_title:"Responsive Filemanager" ,
                      external_plugins: { "filemanager" : '{{ url('/') }}'.replace('/index.php','')+"/filemanager2/plugin.min.js"},
                      setup: function (editor) {
                      
                          /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                          /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                          editor.ui.registry.addMenuButton('menuDateButton', {
                            text: getI18n('editor.insert_tag'),
                            fetch: function (callback) {
                              var items = [];

                              thisEditor.tags.forEach(function(tag) {
                                  if ( tag.type == 'label') {
                                      items.push({
                                          type: 'menuitem',
                                          text: tag.tag.replace("{", "").replace("}", ""),
                                          onAction: function (_) {
                                              if (tag.text) {
                                                  editor.insertContent(tag.text);
                                              } else {
                                                  editor.insertContent(tag.tag);
                                              }                                            
                                          }
                                      });
                                  }
                              });
                              
                              callback(items);
                            }
                          });
                      }
                  };

                  var unsupported_types = 'td, table, img, body';
                  if (!container.is(unsupported_types) && (container.is('[builder-inline-edit]') || !editor.strict)) {
                      container.addClass('builder-class-tinymce');
                      tinyconfig.selector = '.builder-class-tinymce';
                      editor.tinymce = $("#builder_iframe")[0].contentWindow.tinymce.init(tinyconfig);

                      container.removeClass('builder-class-tinymce');
                  }

                  // fixing td tinymce
                  if (container.is('td')) {
                      if (!container.find('.tinymce-td-fix').length) {
                          var span = $('<div class="tinymce-td-fix builder-class-tinymce">');
                          span.html(container.html());

                          container.html('');
                          container.append(span);

                          span.click();
                      }
                  }
                }
            });

            // product widgets
            editor.addWidget(new ProductListWidget(), {
                index: 0,
                group: '{{ trans('builder.widget.e_commerce') }}',
            });
            editor.addWidget(new ProductWidget(), {
                index: 0,
                group: '{{ trans('builder.widget.e_commerce') }}',
            });

            // Rss widget
            editor.addWidget(new RssWidget(), {
                index: 3
            });
          
            editor.init();

            //
            $(document).on('click', '.filemanager-ok', function(e) {alert('{{ trans('builder.widget.click_thumb_to_insert') }}');})
            $(document).on('click', '.filemanager-cancel', function(e) {$('.PopUpCloseButton').click();})

            //
            var urlFill = new UrlAutoFill({!! json_encode($campaign->template->urlTagsDropdown()) !!});
        });
    </script>
  </head>
  <body>
        <style>
            .lds-dual-ring {
                display: inline-block;
                width: 80px;
                height: 80px;
            }
            .lds-dual-ring:after {
                content: " ";
                display: block;
                width: 30px;
                height: 30px;
                margin: 4px;
                border-radius: 80%;
                border: 2px solid #aaa;
                border-color: #007bff transparent #007bff transparent;
                animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        <div style="text-align: center;
            height: 100vh;
            vertical-align: middle;
            padding: auto;
            display: flex;">
            <div style="margin:auto" class="lds-dual-ring"></div>
        </div>
  </body>
</html>
