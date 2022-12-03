<script>
    // cart items element
    class AbandonedCartElement extends SuperElement  {
        name() {
            return getI18n('block');
        }
        icon() {
            return 'fal fa-font';
        }

        preview() {
            var element = this;

            element.obj.addClass('loading');

            var url = '{{ action('ProductController@widgetProductList') }}';
            $.ajax({
                method: "GET",
                url: url,
                data: {
                    per_page: element.obj.attr('data-max-items'),
                    sort_by: element.obj.attr('data-sort-by')
                }
            })
            .done(function( data ) {
                element.obj.attr('preview', 'yes');
                
                element.obj.find('.products').html('');
                data.forEach( function(item) {
                    var cols = (12/element.obj.attr('data-display'));
                    var midCols = cols > 6 ? 12 : 6;
                    var row = `
                        <div class="woo-col-item mb-4 mt-4 col-12 col-sm-`+midCols+` col-md-` +(12/element.obj.attr('data-display'))+ `">
                            <div class="">
                                <div class="img-col mb-3">
                                    <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <a style="width:100%" href="`+item.link+`" class="mr-4"><img width="100%" src="`+(item.image ? item.image : '{{ url('images/cart_item.svg') }}')+`" style="max-height:200px;max-width:100%;" /></a>
                                    </div>
                                </div>
                                <div class="">
                                    <p class="font-weight-normal product-name mb-1">
                                        <a style="color: #333;" href="`+item.link+`" class="mr-4">`+item.name+`</a>
                                    </p>
                                    <p class=" product-description">`+item.description+`</p>
                                    <p><strong>`+item.price+`</strong></p>
                                    <a href="`+item.link+`" style="background-color: #9b5c8f;
border-color: #9b5c8f;" class="btn btn-primary text-white">
                                        {{ trans('messages.automation.buy_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;

                    element.obj.find('.products').append(row);
                });

                if (element.obj.attr('data-display') == '1') {
                    element.obj.find('.img-col').attr('style', 'float: left;margin-right: 20px;width: 150px;margin-bottom:0!important');
                } else {
                    element.obj.find('.img-col').attr('style', '');
                }

                if (editor.selected != null) {
                    editor.selected.select();
                }

                element.obj.removeClass('loading');
            });         
        }

        unpreview() {
            var element = this;

            element.obj.addClass('loading');

            element.display(element.obj.attr('data-display'));

            element.obj.attr('preview', 'no');

            if (editor.selected != null) {
                editor.selected.select();
            }

            element.obj.removeClass('loading');
        }

        display(display) {
            var element = this;

            element.obj.find('.products').html('');
            var preItems = element.obj.attr('data-max-items') > display ? display : element.obj.attr('data-max-items');
            for(var i=0; i<preItems;i++) {
                element.obj.find('.products').append(`
                    <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-4">
                        <div class="">
                            <div class="img-col mb-3">
                                <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                            </div>
                            <div class="">
                                <p class="font-weight-normal product-name mb-1">
                                    <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                </p>
                                <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
border-color: #9b5c8f;" class="btn btn-primary text-white">
                                    {{ trans('messages.automation.buy_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                `);
            }

            element.obj.find('.woo-col-item').removeClass(function (index, className) {
                return (className.match (/(^|\s)col-md-\S+/g) || []).join(' ');
            });

            element.obj.find('.woo-col-item').removeClass(function (index, className) {
                return (className.match (/(^|\s)col-sm-\S+/g) || []).join(' ');
            });

            element.obj.find('.woo-col-item').addClass('col-md-' + (12/display));

            element.obj.find('.woo-col-item').addClass('col-sm-' + ((12/display) > 6 ? 12 : 6));

            if (display == '1') {
                element.obj.find('.img-col').attr('style', 'float: left;margin-right: 20px;width: 150px;margin-bottom:0!important');
            } else {
                element.obj.find('.img-col').attr('style', '');
            }

            if (element.obj.attr('data-max-items') > display) {
                element.obj.find('.products').addClass('more');
            } else {
                element.obj.find('.products').removeClass('more');
            }
        }

        getControls() {
            var element = this;

            return [
                new ProductListControl('{{ trans('messages.woo_items.number_of_items') }}', {
                        max_items: element.obj.attr('data-max-items'),
                        display: element.obj.attr('data-display'),
                        sort_by: element.obj.attr('data-sort-by'),
                        preview: element.obj.attr('preview'),
                    } , {
                        setMaxItems: function(max_items) {
                            element.obj.attr('data-max-items', max_items);                                

                            if (element.obj.attr('preview') == 'yes') {
                                element.preview();
                            } else {
                                element.display(element.obj.attr('data-display'));
                            }

                            element.select();
                        },
                        setDisplay: function(display) {
                            element.obj.attr('data-display', display);     

                            if (element.obj.attr('preview') == 'yes') {
                                element.preview();
                            } else {
                                element.display(element.obj.attr('data-display'));
                            }

                            element.select();
                        },
                        setSortBy: function(sort_by) {
                            element.obj.attr('data-sort-by', sort_by);

                            if (element.obj.attr('preview') == 'yes') {
                                element.preview();
                            } else {
                                element.display(element.obj.attr('data-display'));
                            }

                            element.select();
                        },
                        preview: function() {
                            element.preview();
                        },
                    }
                ),
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
</script>