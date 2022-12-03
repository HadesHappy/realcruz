@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mt-0 mb-4">{{ trans('messages.template.change_name') }}</h2>
            <p class="mb-1">{{ trans('messages.template.change_name.intro') }}</p>
            
            
            <form id="changeNameForm" action="{{ action('TemplateController@changeName', [
                'uid' => $template->uid,
            ]) }}"
                method="POST"
            >
                {{ csrf_field() }}

                @include('helpers.form_control', [
                    'type' => 'text',
                    'label' => '',
                    'name' => 'name',
                    'value' => request()->has('name') ? request()->name : $template->name,
                ])
				
                <div class="mt-20">
                    <button class="btn btn-primary bg-grey-600 me-1">{{ trans('messages.save') }}</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        $(function() {
            $('#changeNameForm').submit(function(e) {
                e.preventDefault();        
                var url = $(this).attr('action');
                var data = $(this).serialize();

                addMaskLoading();

                // 
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false                   
                }).done(function (response) {
                    removeMaskLoading();

                    notify({
                        type: response.status,
                        message: response.message,
                    });

                    TemplatesList.getChangeNamePopup().hide();
                    TemplatesIndex.getList().load();
                }).fail(function (response) {
                    removeMaskLoading();
                    TemplatesList.getChangeNamePopup().loadHtml(response.responseText);
                });
            });
        });
    </script>
@endsection