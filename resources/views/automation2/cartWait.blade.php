@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="mc_section">
                <form class="cart-wait" action="{{ action('Automation2Controller@cartWait', $automation->uid) }}" method="POST">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="plan[options][billing_cycle]" value="other" />
                    
                    <h2 class="text-semibold">{{ trans('messages.cart.wait') }}</h2>
                    
                    <p>{!! trans('messages.cart.wait.wording') !!}</p>
                        
                    <div class="row">
                        <div class="col-md-6">
                            @include('helpers.form_control', [
                                'class' => 'numeric',
                                'type' => 'number',
                                'name' => 'amount',
                                'value' => $trigger->getOption('wait') ? explode('_', $trigger->getOption('wait'))[0] : '1',
                                'help_class' => 'plan',
                                'rules' => ['amount' => 'required'],
                                'required' => true,
                            ])
                        </div>
                        <div class="col-md-6">                        
                            @include('helpers.form_control', ['type' => 'select',
                                'name' => 'unit',
                                'value' => $trigger->getOption('wait') ? explode('_', $trigger->getOption('wait'))[1] : 'hour',
                                'options' => Acelle\Model\Automation2::cartWaitTimeUnitOptions(),
                                'help_class' => 'plan',
                            ])
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                    <a href="javascript:;" onclick="cartWait.hide()" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.close') }}</a>
                </form>
            </div>
        </div>
    </div>
        
    <script>
        $('form.cart-wait').on('submit', function(e) {
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
                        cartWait.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    cartWait.hide();

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