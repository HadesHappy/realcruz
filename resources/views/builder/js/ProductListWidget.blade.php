<script>
    // Product Cart Items Widget
    class ProductListWidget extends Widget {
        getHtmlId() {
            return "ProductListWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('builder.widget.product_list') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget">
                                <img builder-element src="{{ url('images/wooproductlist.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('builder.widget.product_list') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div id="`+this.id+`" builder-element="ProductListElement" class="product-list-widget"
                    data-preview="no"
                    data-count="3"
                    data-cols="3"
                    data-sort="created_at-asc"
                >
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }

        getElement() {
            return currentEditor.getIframeContent().find('#' + this.id);
        }

        drop() {
            var element = currentEditor.elementFactory(this.getElement());
            element.render();

            currentEditor.select(element);
            currentEditor.handleSelect();
        }
    }
</script>