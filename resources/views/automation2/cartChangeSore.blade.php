@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="mc_section">
                <form class="cart-change-store"
                    action="{{ action('Automation2Controller@cartChangeStore', $automation->uid) }}"
                    class=""
                    method="POST">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="plan[options][billing_cycle]" value="other" />
                    
                    <h2 class="text-semibold">{{ trans('messages.cart.store') }}</h2>
                    
                    <p>{!! trans('messages.cart.store.wording') !!}</p>
                        
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                    <a href="javascript:;" onclick="changeStore.hide()" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.close') }}</a>
                </form>
            </div>
        </div>
    </div>
        
    <script>
        $('.cart-change-store').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                statusCode: {
                    // validate error
                    400: function (res) {
                        changeStore.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    changeStore.hide();

                    tree.setOptions($.extend(tree.getOptions(), response.options));

                    // save tree
                    saveData(function() {
                        // notify
                        notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                        
                        // reload sidebar
                        sidebar.load();
                    });
                }
            });
        });
    </script>
@endsection