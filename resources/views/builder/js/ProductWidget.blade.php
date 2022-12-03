<script>
    // Product Cart Item Widget
    class ProductWidget extends Widget {
        getHtmlId() {
            return "ProductWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('builder.widget.product') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget single">
                                <img builder-element src="{{ url('images/product-icon.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('builder.widget.product') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div id="`+this.id+`" builder-element="ProductElement" class="product-widget"
                    data-preview="no"
                    data-display="full"
                    data-id=""
                    data-name=""
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