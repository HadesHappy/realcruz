<script>
    // Rss Widget
    class RssWidget extends Widget {
        getHtmlId() {
            return "RssWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('messages.automation.woo_item') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget">
                                <img builder-element style="width:50px;opacity:0.5" src="{{ url('images/rss_widget.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('messages.widget.rss') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="RssElement" data-url="" data-count="10">
                    <div class="container">
                        <div class="rss-placeholder">
                            <img src="{{ url('images/rss-placeholder.svg') }}" width="100%" />
                            <button class="btn btn-secondary toggle shadown-sm" style="display:none">{{ trans('messages.rss.preview') }}</button>
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // rss element
    class RssElement extends SuperElement  {
        name() {
            var element = this; 
            return getI18n('block');
        }

        preview() {
            var element = this;

            var url = element.obj.attr('data-url');
            var count = element.obj.attr('data-count');

            if (url == '') {
                alert('{{ trans('messages.rss.url_required') }}');
                return;
            }

            alert(url + ' [' + count + ']');

            element.obj.attr('preview', 'on');
            element.obj.find('.btn').html('{{ trans('messages.rss.close') }}');
        }

        unpreview() {
            this.obj.attr('preview', 'off');
            this.obj.find('.btn').html('{{ trans('messages.rss.preview') }}');
        }

        getControls() {
            var element = this;            

            // preview click
            element.obj.find('.toggle').on('click', function(e) {
                $(this).removeClass('toggle');

                if (typeof(element.obj.attr('preview')) == 'undefined' || element.obj.attr('preview') == 'off') {
                    element.preview();
                } else {
                    element.unpreview();
                }
            });

            return [
                new RssControl(getI18n('font_family'), {
                    'url': element.obj.attr('data-url'),
                    'count': element.obj.attr('data-count')
                }, {
                    setUrl: function(url) {
                        element.obj.attr('data-url', url);
                    },
                    setCount: function(count) {
                        element.obj.attr('data-count', count);
                    }
                }),
                new FontFamilyControl(getI18n('font_family'), element.obj.css('font-family'), function(font_family) {
                    element.obj.css('font-family', font_family);
                    element.select();
                }),
                new BackgroundImageControl(getI18n('background_image'), {
                    image: element.obj.css('background-image'),
                    color: element.obj.css('background-color'),
                    repeat: element.obj.css('background-repeat'),
                    position: element.obj.css('background-position'),
                    size: element.obj.css('background-size'),
                }, {
                    setBackgroundImage: function (image) {
                        element.obj.css('background-image', image);
                    },
                    setBackgroundColor: function (color) {
                        element.obj.css('background-color', color);
                    },
                    setBackgroundRepeat: function (repeat) {
                        element.obj.css('background-repeat', repeat);
                    },
                    setBackgroundPosition: function (position) {
                        element.obj.css('background-position', position);
                    },
                    setBackgroundSize: function (size) {
                        element.obj.css('background-size', size);
                    },
                }),
                new BlockOptionControl(getI18n('block_options'), { padding: element.obj.css('padding'), top: element.obj.css('padding-top'), bottom: element.obj.css('padding-bottom'), right: element.obj.css('padding-right'), left: element.obj.css('padding-left') }, function(options) {
                    element.obj.css('padding', options.padding);
                    element.obj.css('padding-top', options.top);
                    element.obj.css('padding-bottom', options.bottom);
                    element.obj.css('padding-right', options.right);
                    element.obj.css('padding-left', options.left);
                    element.select();
                })
            ];
        }
    }

    // Rss Control
    class RssControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="RssControl">
                    <div class="control-[ID]">
                        <div class="widget-row px-3 py-2 d-flex align-items-center">
                            <div class="label mr-auto">{{ trans('messages.rss.url') }}</div>
                            <div class="place-value">
                                <input type="text" value="" class="form-control rss-url">
                            </div>
                        </div>
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
                </div>
            `;
            thisControl.selector = ".control-" + thisControl.id;

            html = html.replace("[ID]", thisControl.id);

            var div = $('<DIV>').html(html);
            
            return div.html();
        }

        afterRender() {
            var thisControl = this;

            // set value
            $(thisControl.selector).find('.rss-url').val(thisControl.value.url);
            $(thisControl.selector).find('.rss-count').val(thisControl.value.count);

            // set url
            $(thisControl.selector).find('.rss-url').on('change keyup', function(e) {
                thisControl.callback.setUrl($(this).val());
            });

            // set count
            $(thisControl.selector).find('.rss-count').on('change', function(e) {
                thisControl.callback.setCount($(this).val());
            });
        }
    }
</script>