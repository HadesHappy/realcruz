<script>
    // Cart items Control
    class ProductControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="ProductListControl">
                    <div class="control-[ID]">
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_item.product') }}</div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:;" class="btn btn-default but-toggle btn-widget-preview mr-1">
                                    <span class="material-symbols-rounded">visibility</span>
                                </a>
                                <a href="javascript:;" class="btn btn-default but-toggle btn-widget-unpreview mr-1">
                                    <span class="material-symbols-rounded">visibility_off</span>
                                </a>
                                <select class="product_id form-control">
                                    <option value="">Select product</option>
                                </select>                                    
                            </div>
                        </div>
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_items.view_option') }}</div>
                            <select class="display-option form-control">
                                <option value="full">{{ trans('messages.woo_items.view_option.full') }}</option>
                                <option value="compact">{{ trans('messages.woo_items.view_option.compact') }}</option>
                                <option value="no_image">{{ trans('messages.woo_items.view_option.no_image') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            thisControl.selector = ".control-" + thisControl.id;

            html = html.replace("[ID]", thisControl.id);
            html = html.replace("[TITLE]", thisControl.title);

            var div = $('<DIV>').html(html);
            
            return div.html();
        }

        getValues() {
            var thisControl = this;
            
            var url = '{{ action('ProductController@widgetProductOptions') }}';

            // set product id
            if (thisControl.value.id) {
                $(thisControl.selector).find('.product_id').append('<option value="'+thisControl.value.id+'">'+thisControl.value.name+'</option>');
                $(thisControl.selector).find('.product_id').val(thisControl.value.id);
            }

            // select2 control
            $(thisControl.selector).find('.product_id').select2({
                placeholder: 'Select product',
                allowClear: false,
                width: 'resolve',
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 0,
            });

            // display
            var display = 'full';
            if (thisControl.value.display) {
                display = thisControl.value.display;
            }
            $(thisControl.selector).find('.display-option').val(display);

            // preview
            $(thisControl.selector).find('.but-toggle').hide();
            if (thisControl.value.id != '' && thisControl.value.preview == 'yes') {
                $(thisControl.selector).find('.btn-widget-unpreview').show();
            }
            if (thisControl.value.id != '' && thisControl.value.preview == 'no') {
                $(thisControl.selector).find('.btn-widget-preview').show();
            }
        }

        afterRender() {
            var thisControl = this;

            // copy url
            $(thisControl.selector).find('.product_id').on('change', function(e) {
                console.log($(this).find('option:selected'));
                thisControl.callback.setOptions({
                    id: $(this).val(),
                    name: $(this).find('option:selected').html()
                });
            });
            
            // preview
            $(thisControl.selector).find('.btn-widget-preview').on('click', function(e) {
                thisControl.callback.setOptions({
                    preview: 'yes',
                });                      
            });

            // unpreview
            $(thisControl.selector).find('.btn-widget-unpreview').on('click', function(e) {
                thisControl.callback.setOptions({
                    preview: 'no',
                });
            });
            
            // display
            $(thisControl.selector).find('.display-option').on('change', function(e) {
                thisControl.callback.setOptions({
                    display: $(this).val(),
                });
            });

            // get values
            thisControl.getValues();
        }
    }
</script>