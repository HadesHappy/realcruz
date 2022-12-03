<script>
    // IMG element
    class ProductImgElement extends SuperElement {
        name() {
            return getI18n('image');
        }
        icon() {
            return 'fal fa-image';
        }

        getControls() {
            var element = this;

            var link = element.obj.parent().length && element.obj.parent().is("a") ? element.obj.parent().attr('href') : '';

            return [
                new ImageControl(getI18n('align'), { readonly: true, width: element.obj.css('width'), src: element.obj.attr('src'), alt: element.obj.attr('alt'), auto_width: element.obj.attr('width') == 'auto'}, function(options) {
                    element.obj.css('width', options.range);
                    element.obj.parent().css('text-align', options.align);
                    element.obj.css('margin', 'auto');
                    element.obj.attr('src', options.src);
                    element.obj.addClass('image-after-change');
                    setTimeout(function() {
                        currentEditor.select(element);
                        if (typeof(options.src) != 'undefined') {
                            currentEditor.handleSelect();
                        }
                    }, 100);
                    
                    if (options.auto_width) {
                        element.obj.css('width', 'auto');
                    }
                }),
                new ImageSizeControl(getI18n('image_size'), {
                    width: element.obj.width(),
                    height: element.obj.height()
                }, function(options) {
                    element.obj.width(options.width);
                    element.obj.height(options.height);
                }),

                //            
                new ImageLinkControl(getI18n('image_link'), {
                    readonly: true,
                    url: link
                }, function(options) {
                    if (element.obj.parent().is("a")) {
                        element.obj.parent().attr('href', options.url);
                    } else {
                        element.obj.wrap( "<a href='" + options.url + "'></a>" );
                    }
                }),
                new BlockOptionControl(getI18n('block_options'), { padding: element.obj.css('padding'), top: element.obj.css('padding-top'), bottom: element.obj.css('padding-bottom'), right: element.obj.css('padding-right'), left: element.obj.css('padding-left') }, function(options) {
                    // apply ngược lại cho element.obj
                    element.obj.css('padding', options.padding);
                    element.obj.css('padding-top', options.top);
                    element.obj.css('padding-bottom', options.bottom);
                    element.obj.css('padding-right', options.right);
                    element.obj.css('padding-left', options.left);
                    setTimeout(function() {
                        currentEditor.select(element);
                    }, 100);
                }),
            ];
        }
    }
</script>