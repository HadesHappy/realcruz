@if (null !== Session::get('orig_customer_id') && Auth::user()->customer)
    <a href="{{ action('CustomerController@adminArea') }}" class="user-switch-area mc-modal-control">
        {{ trans('messages.customer.admin_area') }}
    </a>
    <script>
        var AdminAreaPopup;

        $(function() {
            $('.user-switch-area').on('click', function(e) {
                e.preventDefault();
                AdminAreaPopup = new Popup({
                    url: '{{ action('CustomerController@adminArea') }}',
                });

                AdminAreaPopup.load();
            });
        });
            
    </script>
@endif