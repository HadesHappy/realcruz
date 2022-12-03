<script>
    // Rss Control
    class RssControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="RssControl">
                    <div class="control-[ID]">
                        <div class="px-3 mt-3">
                            <h4>{{ trans('messages.widget.rss') }}</h4>
                            <p>{{ trans('messages.widget.rss.desc') }}</p>
                        </div>
                        <div class="start-block">
                            <div class="px-3 py-2 d-flex align-items-center border-bottom">
                                <button class="click-start btn btn-primary" style="width:100%">
                                    {{ trans('messages.widget.rss.click_start') }}
                                </button>
                            </div>
                        </div>
                        <div class="setting-block">
                            <div class="widget-row px-3 py-2 d-flex align-items-center ">
                                <div class="place-value" style="width:100%">
                                    <input type="text" value="" class="form-control rss-url">
                                </div>
                                <button class="click-load btn btn-secondary ml-2">
                                    {{ trans('messages.widget.rss.load_url') }}
                                </button>
                                <button class="click-change btn btn-secondary ml-2">
                                    {{ trans('messages.widget.rss.change_url') }}
                                </button>
                            </div>
                            <div class="more-options-block">
                                <div class="display-count">
                                    <div class="widget-row px-3 py-2 d-flex align-items-center">
                                        <div class="label mr-auto">{{ trans('messages.rss.posts') }}</div>
                                        <div class="place-value">
                                            <select class="form-control rss-count">
                                                @for ($i = 1; $i < 50; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor        
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-3 mt-4">
                                    <h4>{{ trans('messages.rss.display_options') }}</h4>
                                </div>
                                <div class="templates-block">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            `;
            thisControl.selector = ".control-" + thisControl.id;

            html = html.replace("[ID]", thisControl.id);

            var div = $('<DIV>').html(html);
            
            return div.html();
        }

        showStart() {
            var thisControl = this;

            $(thisControl.selector).find('.start-block').show();
            $(thisControl.selector).find('.setting-block').hide();
        }

        showChangeUrl() {
            var thisControl = this;

            $(thisControl.selector).find('.start-block').hide();
            $(thisControl.selector).find('.setting-block').show();

            // 
            $(thisControl.selector).find('.rss-url').prop('readonly', false);
            $(thisControl.selector).find('.click-load').show();
            $(thisControl.selector).find('.click-change').hide();
            $(thisControl.selector).find('.more-options-block').hide();
        }

        loadUrl() {
            var thisControl = this;

            if (thisControl.getUrl() == '') {
                alert('{{ trans('messages.rss.url_required') }}');
                return;
            }

            currentEditor.mask();

            $.ajax({
                url: '{!! action('TemplateController@parseRss') !!}',
                method: 'GET',
                data: {
                    config: $.extend({}, thisControl.value.config, {
                        url: thisControl.getUrl()
                    })
                }
            }).fail(function(e) {
                alert(e.responseText);
                currentEditor.unmask();
            }).done(function() {
                thisControl.callback.setOptions({
                    config: $.extend({}, thisControl.value.config, {
                        url: thisControl.getUrl()
                    }),
                    preview: 'yes',
                });
                currentEditor.unmask();
                thisControl.showLoadUrl();
            });
        }

        showLoadUrl() {
            var thisControl = this;

            $(thisControl.selector).find('.start-block').hide();
            $(thisControl.selector).find('.setting-block').show();

            // 
            $(thisControl.selector).find('.rss-url').prop('readonly', true);
            $(thisControl.selector).find('.click-load').hide();
            $(thisControl.selector).find('.click-change').show();
            $(thisControl.selector).find('.more-options-block').show();
        }

        getUrl() {
            var thisControl = this;

            return $(thisControl.selector).find('.rss-url').val();
        }

        isSelected(className) {
            return this.value.getSelectedItem() && this.value.getSelectedItem().getClassName() == className;
        }

        showDisplayOptions() {
            var _this = this;
            var templates = this.value.config.templates;
            
            $(_this.selector).find('.templates-block').html('');
            for (const [key, t] of Object.entries(templates)) {
                var form = '';
                var showed =  _this.isSelected(key) && t.show == true;

                if (showed && t.template !== false) {
                    form = `
                        <div class="border-top px-3 py-2">
                            <textarea class="rss-template-edit rss-editor-`+key+` form-control template-content" data-class="`+key+`">`+t.template+`</textarea>
                            <div class="mt-2 text-right">
                                <button class="btn btn-primary mr-1 rss-template-save" data-class="`+key+`">{{ trans('messages.rss.template.save') }}</button>
                                <button class="btn btn-secondary rss-template-cancel" data-class="`+key+`">{{ trans('messages.rss.template.cancel') }}</button>
                            </div>
                        </div>
                    `;
                }

                $(_this.selector).find('.templates-block').append(`
                    <div class="rss-config-line `+(showed ? 'item-selected' : '')+`">
                        <div class="d-flex align-items-center px-3 py-2 border-top">
                            <label class="d-flex align-items-center" style="font-size:14px;font-weight:normal;color:#333;">
                                <input type="hidden" name="`+key+`" value="false" />
                                <input `+(t.show == true ? 'checked' : '')+` type="checkbox" class="styled item-show-check" name="`+key+`" value="true" />
                                <span class="check-symbol"></span>
                                <span class="ml-2">{!! trans('messages.rss.show_msg') !!} <span class="fw-600">`+t.title+`</span></span>
                            </label> 
                            <div class="ml-auto" `+(!t.show || showed || t.template === false ? 'style="display:none"' : '')+`>
                                <a href="javascript:;" class="setting-button" data-class="`+key+`">
                                    <span class="material-symbols-rounded">settings</span>
                                </a>
                            </div>              
                        </div>
                        `+form+`           
                    </div>
                `);

                // events
                $(_this.selector).find('.templates-block [name="'+key+'"]').on('change', function() {
                    var checked = $(this).is(':checked');
                    _this.callback.saveItemShow(key, checked)

                    if (_this.isSelected(key) && !checked) {
                        _this.callback.unselectItem();
                    }
                });
                
                $(_this.selector).find('.setting-button[data-class="'+key+'"]').on('click', function() {
                    _this.callback.selectItemByClass(key);
                });

                $(_this.selector).find('.rss-template-save[data-class="'+key+'"]').on('click', function() {
                    var content = $(_this.selector).find('.template-content[data-class="'+key+'"]').val();
                    _this.callback.saveItemTemplate(key, content);
                });

                $(_this.selector).find('.rss-template-cancel[data-class="'+key+'"]').on('click', function() {
                    _this.callback.unselectItem();
                });

                var tinyconfig = {
                    selector: '.rss-editor-' + key,
                    valid_elements: '*[*]',
                    valid_children: '+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div],*[*]',
                    branding: false,
                    menubar: false,
                    skin: "oxide",
                    height: '200px',
                    elementpath: false,
                    force_br_newlines : false,
                    relative_urls: false,
                    convert_urls: false,
                    remove_script_host : false,
                    force_p_newlines : false,
                    forced_root_block : '',
                    inline_boundaries: false,
                    allow_html_in_named_anchor: true,
                    plugins: 'link lists autolink image',

                    external_filemanager_path:'{{ url('/') }}'.replace('/index.php','')+"/filemanager2/",
                    filemanager_title:"Responsive Filemanager" ,
                    external_plugins: { "filemanager" : '{{ url('/') }}'.replace('/index.php','')+"/filemanager2/plugin.min.js"},

                    //toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent',
                    toolbar: 'link image undo redo bold italic underline strikethrough fontselect fontsizeselect forecolor backcolor casechange customTag custom1 custom2',
                    setup: function (editor) {
                    
                        /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                        /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                        editor.ui.registry.addMenuButton('customTag', {
                        text: getI18n('editor.insert_tag'),
                        fetch: function (callback) {
                            var items = [];
                            var tags = {!! json_encode(rssTags()) !!};

                            if (key.includes('Item')) {
                                tags = tags.item;
                            } else {
                                tags = tags.feed;
                            }

                            tags.forEach(function(tag) {
                                items.push({
                                    type: 'menuitem',
                                    text: tag,
                                    onAction: function (_) {
                                        if (tag.text) {
                                            editor.insertContent(tag);
                                        } else {
                                            editor.insertContent(tag);
                                        }                                            
                                    }
                                });
                            });
                            
                            callback(items);
                        }
                        });

                        editor.on("change keyup", function(e){
                            editor.save(); // updates this instance's textarea
                            $(editor.getElement()).trigger('change'); // for garlic to detect change
                            // thisControl.callback(editor.getContent());
                            // currentEditor.selected.select();
                        });
                    }
                };
                tinymce.init(tinyconfig);
            };
        }

        afterRender() {
            var thisControl = this;

            // show blocks
            if (thisControl.value.config.url == '') {
                thisControl.showStart();
            } else {
                thisControl.showLoadUrl();
            }

            // render options
            thisControl.showDisplayOptions();


            // set value
            $(thisControl.selector).find('.rss-url').val(thisControl.value.config.url);
            $(thisControl.selector).find('.rss-count').val(thisControl.value.config.size);

            // set count
            $(thisControl.selector).find('.rss-count').on('change', function(e) {
                thisControl.callback.setOptions({
                    config: {
                        size: $(this).val()
                    }                    
                });
            });
            
            // click start
            $(thisControl.selector).find('.click-start').on('click', function(e) {
                thisControl.showChangeUrl();
            });

            // click change
            $(thisControl.selector).find('.click-change').on('click', function(e) {
                thisControl.showChangeUrl();
            });

            // load url
            $(thisControl.selector).find('.click-load').on('click', function(e) {
                thisControl.loadUrl();
            });
        }
    }
</script>