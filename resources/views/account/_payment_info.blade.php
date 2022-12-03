@if (request()->user()->customer->getPreferredPaymentGateway() !== null)
    <div class="payment-box">
        <div class="header py-4 d-flex align-items-center">
            <div class="d-flex align-items-center">
                <i class="material-symbols-rounded me-2 bg-{{ request()->user()->customer->getPreferredPaymentGateway()->getType() }}">
                    
                </i>
                <span class="font-weight-semibold">{{ request()->user()->customer->getPreferredPaymentGateway()->getName() }}</span>
            </div>
            <div class="ml-auto">
                <a href="{{ action('AccountController@removePaymentMethod') }}" class="payment-method-remove">
                    {{ trans('messages.remove') }}
                </a>
            </div>
        </div>
        <div class="body">
            <div class="bill_info">
                <div class="line d-flex my-2">
                    {{ trans('messages.'.request()->user()->customer->getPreferredPaymentGateway()->getType().'.user.description') }}
                </div>
            </div>
        </div>
    </div>

    <a href="{{ action('AccountController@editPaymentMethod', [
        'redirect' => isset($redirect) ? $redirect : action('AccountController@billing'),
    ]) }}" class="btn btn-secondary payment-method-edit mt-4">
        {{ trans('messages.change_payment_method') }}
    </a>
@else
    <p>{{ trans('messages.have_no_payment_method') }}</p>

    <a href="{{ action('AccountController@editPaymentMethod') }}"
        class="btn btn-secondary payment-method-edit">
        {{ trans('messages.add_payment_method') }}
    </a>
@endif

<script>
    var paymentPopup = new Popup();
    
    $('.payment-method-edit').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        paymentPopup.load(url);
    });
    
    $('.payment-method-remove').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        var dia = new Dialog('confirm', {
            message: '{{  trans('messages.bill.remove_payment.confirm') }}',
            ok: function() {
                $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN
                },
                success: function (response) {
                    window.location.reload();
                }
            });
            },
        });
    });
</script>