@if ($invoice->isNew() && !$invoice->getPendingTransaction())
    <div class="card shadow-sm border-top-0 rounded-bottom p-2" style="margin-top: -10px;
    border-radius: 0;">
        <div class="card-body pe-4 ps-4 pb-4 pt-0">
            <div class="alert alert-info bg-no-order tos_link">
                @if (Acelle\Model\Setting::get('terms_of_service.enabled') == 'no' && Acelle\Model\Setting::get('terms_of_service.content')) 
                    {!! trans('messages.payment.agree_service_intro.term_link', [
                        'link' => 'asas',
                    ]) !!}
                @else
                    {!! trans('messages.payment.agree_service_intro') !!}
                @endif
            </div>
            @if (!request()->payment_method && !$invoice->isFree())
                
                <form id="checkoutForm" class="" action="{{ $invoice->isFree() ? action('SubscriptionController@confirmFree', [
                    'invoice_uid' => $invoice->uid
                ]) : action('SubscriptionController@checkout') }}"
                    method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="payment_method" />

                    <input type="submit" name="new_payment" disabled
                        class="btn btn-secondary py-2 fs-6 px-4 text-center rounded-3 shadow" style="width:100%"
                        value="{{ $invoice->isFree() ? trans('messages.subscription.get_started') : trans('messages.subscription.checkout') }}"
                    >
                </form>
            @else

                <form id="checkoutForm" class="" action="{{ $invoice->isFree() ? action('SubscriptionController@confirmFree', [
                    'invoice_uid' => $invoice->uid
                ]) : action('SubscriptionController@checkout') }}"
                    method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="payment_method" value="{{ request()->payment_method }}" />

                    <input type="submit" name="new_payment"
                        class="btn btn-primary py-2 fs-6 px-4 text-center rounded-3 shadow" style="width:100%"
                        value="{{ $invoice->isFree() ? trans('messages.subscription.get_started') : trans('messages.subscription.checkout') }}"
                    >
                </form>
            @endif
        </div>
    </div>

    @if ($invoice->type == \Acelle\Model\Invoice::TYPE_CHANGE_PLAN)
        <div class="text-center mt-4">
            <a class="btn btn-link text-center rounded-3"
                style="width:100%"
                link-method="POST"
                link-confirm="{{ trans('messages.invoice.cancel.confirm') }}"
                href="{{ action('SubscriptionController@cancelInvoice', [
                    'invoice_uid' => $bill['invoice_uid'],
                ]) }}"
            >
                {{ trans('messages.invoice.change_plan.cancel') }}
            </a>
        </div>
    @endif

    <script>
        $(function() {
            @if (!$invoice->isFree())
                $('#checkoutForm').submit(function(e) {
                    var payment_method = $(this).find('[name=payment_method]').val();

                    if (!payment_method) {
                        e.preventDefault();

                        new Dialog('alert', {
                            message: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                        })

                        return false;
                    }
                });
            @endif

            $('.tos_link a').on('click', function(e) {
                e.preventDefault();

                var popup = new Popup({
                    url: '{{ action('Controller@termsOfService') }}'
                });
                popup.load();
            });
        });
    </script>
@endif