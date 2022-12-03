<script>
    // Cart items Control
    class ProductListControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="ProductListControl">
                    <div class="control-[ID]">
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_items.number_of_items') }}</div>
                            <select class="max-items form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_items.display_option') }}</div>
                            <select class="display-option form-control">
                                <option value="1">{{ trans('messages.woo_items.display_option.1_column') }}</option>
                                <option value="2">{{ trans('messages.woo_items.display_option.2_column') }}</option>
                                <option value="3">{{ trans('messages.woo_items.display_option.3_column') }}</option>
                                <option value="4">{{ trans('messages.woo_items.display_option.4_column') }}</option>
                            </select>
                        </div>
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_items.sort_by') }}</div>
                            <select class="sort-by form-control">
                                <option value="price-asc">{{ trans('messages.woo_items.sort_by.price_az') }}</option>
                                <option value="price-desc">{{ trans('messages.woo_items.sort_by.price_za') }}</option>
                                <option value="created_at-asc">{{ trans('messages.woo_items.sort_by.date_added_asc') }}</option>
                                <option value="created_at-desc">{{ trans('messages.woo_items.sort_by.date_added_desc') }}</option>
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
            
            $(thisControl.selector).find('.max-items').val(thisControl.value.count);
            $(thisControl.selector).find('.display-option').val(thisControl.value.cols);
            $(thisControl.selector).find('.sort-by').val(thisControl.value.sort);
        }

        afterRender() {
            var thisControl = this;

            // set max items
            $(thisControl.selector).find('.max-items').on('change', function(e) {
                thisControl.callback.setOptions({
                    count: $(this).val()
                });
            });

            // set display
            $(thisControl.selector).find('.display-option').on('change', function(e) {
                thisControl.callback.setOptions({
                    cols: $(this).val()
                });
            });

            // set sort by
            $(thisControl.selector).find('.sort-by').on('change', function(e) {
                thisControl.callback.setOptions({
                    sort: $(this).val()
                });
            });

            // get values
            thisControl.getValues();
        }
    }
</script>