@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>{{ trans('messages.list.select_customer_for_clone_list', ['list' => $list->name]) }}</h2>
            <p>{{ trans('messages.list.select_customer_for_clone_list.intro') }}</p>
                
            <form id="cloneForm" action="{{ action('MailListController@cloneForCustomers', $list->uid) }}" method="POST" class="form-validate-jqueryz">
                {{ csrf_field() }}
                
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'customers[]',
                    'label' => '',
                    'value' => '',
                    'multiple' => true,
                    'options' => $options,
                    'placeholder' => trans('messages.click_here_select_customer'),
                    'rules' => ['customers' => 'required']
                ])
                
                <button class="btn btn-primary bg-grey-800">{{ trans('messages.list.start_clone') }}</button>
            </form>
            
        </div>
    </div>
        
    <script>
        customValidate($('#cloneForm'));
    
        $('#cloneForm').submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var form = $(this);
            
            
            if(form.valid()) {
                Popup.hide();
                addMaskLoading();
            
                $.ajax({
                    method: "POST",
                    url: url,
                    data: form.serialize(),
                })
                .done(function( data ) {
                    removeMaskLoading();

                    notify({
                        title: "{{ trans('messages.notify.success') }}",
                        message: data.message,
                    });
                });
            }
        });
    </script>
@endsection