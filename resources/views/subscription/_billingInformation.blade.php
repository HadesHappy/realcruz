<div class="card mt-2 subscription-step">
    @if ($invoice ?? false)
        <a href="{{ action('SubscriptionController@billingInformation', [
            'invoice_uid' => $invoice->uid,
        ]) }}" class="card-header py-3 select-plan-tab">
            <div class="d-flex align-items-center">
                <div class="me-3"><label class="subscription-step-number bg-secondary">2</label></div>
                <div>
                    <h5 class="fw-600 mb-0 fs-6 text-start">
                        {{ trans('messages.subscription.billing_information.title') }}
                    </h5>
                    <p class="m-0 text-muted">{{ trans('messages.subscription.billing_information.subtitle') }}</p>
                </div>
                <div class="ms-auto">
                    <span class="material-symbols-rounded fs-4 text-success">
                        task_alt
                        </span>
                </div>
            </div>
        </a>
    @else
        <div class="card-header py-3 select-plan-tab">
            <div class="d-flex align-items-center">
                <div class="me-3"><label class="subscription-step-number bg-secondary">2</label></div>
                <div>
                    <h5 class="fw-600 mb-0 fs-6 text-start">
                        {{ trans('messages.subscription.billing_information.title') }}
                    </h5>
                    <p class="m-0 text-muted">{{ trans('messages.subscription.billing_information.subtitle') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>