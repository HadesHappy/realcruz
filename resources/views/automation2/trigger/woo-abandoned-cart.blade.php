<div class="mb-20">
    <input type="hidden" name="options[type]" value="woo-abandoned-cart" />
    
    @php
        $trigger = $automation->getTrigger();
        $data = $trigger->getOption('source_uid') ? Acelle\Model\Source::findByUid($trigger->getOption('source_uid'))->getData() : null;
        $shopinfo = !$data ? [] : Acelle\Model\Source::findByUid($trigger->getOption('source_uid'))->getData()['data'];
    @endphp
    @if (isset($shopinfo['name']) && !request()->options)
        <div class="cart-settings mb-4">
            <div class="settings">
                <div class="d-flex my-2 py-1">
                    @php
                        $waitTime = $trigger->getOption('wait') ? $trigger->getOption('wait') : '24_hour'
                    @endphp
                    <div class="check-icon mr-4 pt-1">
                        <span class="material-symbols-rounded text-success">
                            check_circle
                        </span>
                    </div>
                    <div class="setting-content">
                        {!! trans('messages.cart.send_notification_email_after', [
                            'time' => trans_choice(
                                'messages.automation.delay.' . explode('_', $waitTime)[1]
                                , explode('_', $waitTime)[0]),
                        ]) !!}
                        <div>
                            <a href="{{ action('Automation2Controller@cartWait', $automation->uid) }}"
                                class="text-underline cart-change-wait">
                                <u>{{ trans('messages.cart.wait.click_change') }}</u>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex my-2 py-1">
                    @if ($trigger->getOption('list_uid'))
                        @php
                            $list = Acelle\Model\MailList::findByUid($trigger->getOption('list_uid'));
                        @endphp
                        <div class="check-icon mr-4 pt-1">
                            <span class="material-symbols-rounded text-success">
                                check_circle
                            </span>
                        </div>
                        <div class="setting-content">
                            {!! trans('messages.cart.auto_add_buyer_to_list', [
                                'list' => $list->name,
                            ]) !!}
                            <div>
                                <a href="{{ action('Automation2Controller@cartChangeList', $automation->uid) }}"
                                    class="text-underline cart-change-list">
                                    <u>{{ trans('messages.cart.auto_add_buyer.click_change') }}</u>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="check-icon mr-4 pt-1">
                            <span class="material-symbols-rounded text-success">
                                check_circle
                            </span>
                        </div>
                        <div class="setting-content">
                            {!! trans('messages.cart.auto_add_buyer_to_list.none') !!}
                            <div>
                                <a href="{{ action('Automation2Controller@cartChangeList', $automation->uid) }}"
                                    class="text-underline cart-change-list">
                                    <u>{{ trans('messages.cart.auto_add_buyer.select_list') }}</u>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex my-2 py-1">
                    <div class="check-icon mr-4 pt-1">
                        <span class="material-symbols-rounded text-success">
                            check_circle
                        </span>
                    </div>
                    <div class="setting-content">
                        {!! trans('messages.cart.connected_to_store', [
                            'store' => $shopinfo['name'],
                        ]) !!}
                        <div>
                            <a href="javascript:;" class="text-underline show-hide-store-info">
                                <u class="show-but">{{ trans('messages.cart.connected_to_store.show') }}</u>
                                <u class="hide-but">{{ trans('messages.cart.connected_to_store.hide') }}</u>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wp-shop-info mb-4 mt-4" style="display:none">
            <div class="d-flex align-items-center">
                <div class="logo-col mr-3">
                    @if (!$shopinfo['logo'])
                        <img src="{{ url('images/Wordpress-Logo.svg') }}" class="shop_logo" />
                    @else
                        {!! $shopinfo['logo'] !!}
                    @endif
                </div>
                <div class="logo-info" style="line-height: 19px">
                    <p class="mb-1">{!! trans('messages.automation.connected_to_store', [
                        'store' => $shopinfo['name']
                    ]) !!}</p>
                    <div class="small">
                        <a target="_blank" href="{{ $shopinfo['url'] }}">{{ trans('messages.automation.visit_store') }}</a>
                    </div>
                </div>
                <div class="ml-auto">
                    <a href="{{ action('Automation2Controller@cartChangeStore', $automation->uid) }}"
                        class="btn btn-secondary change-connect-url text-nowrap"
                        {{-- onclick="$('.edit-connect-url').removeClass('hide');$('.wp-shop-info').hide();$('.trigger-save-change').show()" --}}
                    >
                        {{ trans('messages.automation.connect_url.change_store') }}
                    </a>
                </div>
            </div>
            <div class="bottom-info">
                <p>{{ trans('messages.automation.associated_with_store.wording', [
                    'store' => $shopinfo['name']
                ]) }}</p>
                <div class="d-flex align-items-center store-states justify-content-space-between mt-4">
                    <div class="d-flex align-items-center">
                        <span class="store-icon">
                            {{-- <span class="material-symbols-rounded">
                                loyalty
                            </span> --}}
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{{ $shopinfo['products_count'] }}</label>
                            <div class="text-muted small">{{ trans('messages.woo.products') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center store-states">
                        <span class="store-icon">
                            {{-- <span class="material-symbols-rounded">
                                assignment
                            </span> --}}
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{{ $shopinfo['orders_count'] }}</label>
                            <div class="text-muted small">{{ trans('messages.woo.orders') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center store-states">
                        <span class="store-icon">
                            {{-- <span class="material-symbols-rounded">
                                insights
                            </span> --}}
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{!! $shopinfo['total_sales'] !!}</label>
                            <div class="text-muted small">{{ trans('messages.woo.total_sales') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .trigger-save-change {
                display: none;
            }
        </style>
        <script>
            var listSelect = new Popup();
            var cartWait = new Popup();
            var changeStore = new Popup();

            $('.cart-change-list').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                listSelect.load(url);
            });

            $('.cart-change-wait').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                cartWait.load(url);
            });

            $('.show-hide-store-info').on('click', function(e) {
                var showed = $('.wp-shop-info').is(':visible');

                if (showed) {
                    $(this).removeClass('is-show');
                    $('.wp-shop-info').hide();
                } else {
                    $(this).addClass('is-show');
                    $('.wp-shop-info').show();
                }
            });

            $('.change-connect-url').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                changeStore.load(url);
            });
        </script>
    @endif

    <div class="edit-connect-url {{ isset($shopinfo['name']) && !request()->options ? 'hide' : '' }}">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => '',
            'name' => 'options[source_uid]',
            'value' => $trigger->getOption('source_uid'),
            'options' => request()->user()->customer->getSelectOptions('woocommerce'),
            'help_class' => 'trigger',
            'rules' => [],
        ])
        <input type="hidden" name="options[wait]" value="24_hour" />
    </div>
</div>
