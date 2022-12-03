@if ($bill)
    <div class="card shadow-sm rounded-3 px-2 py-2">
        <div class="card-body p-4">
            <h2 class="fw-600 mb-1 text-start">
                {{ $bill['title'] }}
            </h2>
            <p class="m-0 text-muted">{!! $bill['description'] !!}</p>
            <hr>

            <div>
                @foreach ($bill['bill'] as $item)
                    <div class="bill_item d-flex">
                        <div class="mr-auto">
                            <p class="mb-0 font-weight-semibold">{{ trans('messages.invoice.price') }}</p>
                            <p class="mb-0">({{ $item['title'] }})</p>
                            <p class="mb-0">{!! $item['description'] !!}</p>
                        </div>
                        <div class="font-weight-semibold fs-6"><span>{{ $item['price'] }}</span></div>
                    </div>
                    <div class="bill_item d-flex">
                        <div class="mr-auto">
                            <p class="mb-0 font-weight-semibold">{{ trans('messages.bill.tax') }} ({{ trans('messages.vat.percent', [
                                'percent' => $item['tax_p'],
                            ]) }})</p>
                        </div>
                        <p class="mb-0 font-weight-semibold fs-6">{{ $item['tax'] }}</p>
                    </div>
                @endforeach
            </div>
            <hr>
            @if (isset($bill['fee']))
                <div class="bill_item d-flex">
                    <div class="mr-auto">
                        <p class="mb-0 font-weight-semibold">{{ trans('messages.bill.fee') }}</p>
                    </div>
                    <p class="mb-0 font-weight-semibold fs-6">{{ $bill['fee'] }}</p>
                </div>
                <hr>
            @endif
            <div>
                <div class="total d-flex ">
                    <div class="mr-auto">
                        <p class="mb-0 font-weight-semibold">{{ trans('messages.bill.estimated_total') }}</p>
                        <p class="mb-0">{!! $bill['charge_info'] !!}</p>
                    </div>
                    <p class="mb-0 bill-total fs-5 fw-bold">{{ $bill['total'] }}</p>
                </div>
            </div>
            
            @if ($bill['pending'])
                <hr>
                <div class="text-right pe-none">
                    <a href="{{ action('SubscriptionController@payment', [
                        'invoice_uid' => $bill['invoice_uid'],
                    ]) }}" class="btn btn-warning button-loading full-width pr-20 pe-none " style="width:100%;pointer-events: auto;opacity:0.9">
                        {{ trans('messages.invoice.payment_is_being_verified') }}
                        <div class="loader"></div>
                    </a>
                </div>
            @elseif (
                isset($payButton) &&
                (
                    $bill['type'] == Acelle\Model\Invoice::TYPE_CHANGE_PLAN || 
                    !\Auth::user()->customer->preferredPaymentGatewayCanAutoCharge()
                )
            )
                <hr>
                <div class="text-left">
                    <a href="{{ action('SubscriptionController@payment', [
                        'invoice_uid' => $bill['invoice_uid'],
                    ]) }}" class="btn btn-secondary">
                        {{ trans('messages.invoice.pay_now') }}
                    </a>

                    @if ($bill['type'] == Acelle\Model\Invoice::TYPE_CHANGE_PLAN)
                        <a class="btn btn-link" link-method="POST" link-confirm="{{ trans('messages.invoice.cancel.confirm') }}"
                            href="{{ action('SubscriptionController@cancelInvoice', [
                                'invoice_uid' => $bill['invoice_uid'],
                            ]) }}">
                            {{ trans('messages.invoice.cancel') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif