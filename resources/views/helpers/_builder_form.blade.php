<script>
    // popup banner element
    class PopupBannerElement extends SuperElement {
        name() {
            return getI18n('image');
        }
        icon() {
            return 'fal fa-image';
        }

        getControls() {
            var element = this;

            return [
                new ImageControl(getI18n('align'), {
                    width: element.obj.css('background-size').split(' ')[1],
                    src: element.obj.css('background-image').match(/url\((\"|\')?([^(\"|\')]+)/)[2],
                    alt: '',
                    align: element.obj.css('background-position').split(' ')[0],
                    auto_width: element.obj.css('background-size').split(' ')[1] == '100%'
                }, {
                    setRange: function(range) {
                        element.obj.css('background-size', 'auto ' + range);
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                    setUrl: function(url) {
                        element.obj.css('background-image', 'url("'+url+'")');
                        element.obj.addClass('bg-changed');
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                    setAlign: function(align) {
                        element.obj.css('background-position', align + ' center');
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                    setAlt: function(alt) {
                        
                    },
                    setAutoWidth: function(auto_width) {
                        if (auto_width) {
                            element.obj.css('background-size', 'auto 100%');
                            currentEditor.select(element);
                        }
                    }
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
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                    setBackgroundColor: function (color) {
                        element.obj.css('background-color', color);
                    },
                    setBackgroundRepeat: function (repeat) {
                        element.obj.css('background-repeat', repeat);
                    },
                    setBackgroundPosition: function (position) {
                        element.obj.css('background-position', position);
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                    setBackgroundSize: function (size) {
                        element.obj.css('background-size', size);
                        currentEditor.select(element);
                        currentEditor.handleSelect();
                    },
                })
            ];
        }
    }
</script>