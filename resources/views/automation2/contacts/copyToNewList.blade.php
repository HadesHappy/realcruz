@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form class="copy-new-list" action="{{ action("Automation2Controller@copyToNewList", $automation->uid) }}"
                method="POST" class="form-validate-jqueryz"
            >
                {{ csrf_field() }}

                <input type="hidden" name="action_id" value="{{ request()->action_id }}" />

                <h3 class="mb-3">{{ trans('messages.automation.contacts.copy_to_new_list') }}</h3>
                <p>{!! trans('messages.automation.contacts.copy_to_new_list.intro', [
                    'count' => number_with_delimiter($subscribers->count(), $precision = 0),
                ]) !!}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'label' => '',
                    'name' => 'name',
                    'value' => '',
                    'placeholder' => trans('messages.automation.contacts.copy_to_new_list.enter_list_name'),
                    'help_class' => 'trigger',
                    'rules' => ['name' => 'required'],
                ])

                <div class="mt-4">
                    <button class="btn btn-secondary">{{ trans('messages.automation.contacts.copy_to_new_list.copy') }}</button>
                </div>
        </div>
    </div>

    <script>
        $('.copy-new-list').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var data = form.serialize();
            var url = form.attr('action');
            
            addMaskLoading('{{ trans('messages.automation.contacts.copying_to_new_list') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        copyContact.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // hide popup
                    copyContact.hide();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();

                    // reload sidebar
                    sidebar.load();
                }
            });    
        });
    </script>
@endsection
