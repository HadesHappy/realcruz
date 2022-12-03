@if ($bill)
    @include('invoices.bill', [
        'bill' => $bill,
    ])

    @if (isset($invoice))
        @include('subscription._checkout')
    @endif
@else
    <div class="card shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="px-3">
                <h3 class="fw-600 mb-0 text-start">
                    Your Order
                </h3>
                <hr>
                <div class="alert alert-info bg-no-order mb-0">
                    <p>{{ trans('messages.suscription.order_empty') }}

                    </p>
                </div>
            </div>
        </div>
    </div>
@endif