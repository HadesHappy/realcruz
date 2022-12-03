<script>
    // Product Cart Items Widget
    class AbandonedCartWidget extends Widget {
        getHtmlId() {
            return "AbandonedCartWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('builder.widget.abandoned_cart') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget">
                                <img builder-element style="width:58px" src="{{ url('images/woo_cart.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('builder.widget.abandoned_cart') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div data-items-number="4" builder-element="AbandonedCartElement" data-max-items="4" data-display="4" data-sort-by="created_at-desc" class="product-list-widget">
                    <div class="container">
                        <span class="woo-button product-preview-but" style="display:none">Preview</span>
                        <span class="woo-button product-unpreview-but" style="display:none">Close preview</span>
                        <div class="row py-3 products">
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
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
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
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
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
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
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
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
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }
</script>