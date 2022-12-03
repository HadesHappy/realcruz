@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h3 class="mb-3 mt-0">
                {{ trans('messages.source.woo.connect') }}
            </h3>
            <p class="mb-10">
                {!! trans('messages.source.woo.desc', [
                    'link' => route('download', ['name' => 'acelle-sync.zip']),
                ]) !!}
			</p>
				
			<form id="connectWoo" action="{{ action("SourceController@wooConnect") }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}

                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'placeholder' => trans('messages.source.connect_url.help'),
                    'name' => 'connect_url',
                    'value' => '',
                    'help_class' => 'trigger',
                    'rules' => [],
                ])
				
				<button class="btn btn-secondary select-trigger-confirm mt-2"
					
				>
					{{ trans('messages.automation.trigger.select_confirm') }}
				</button>
			</form>
        </div>
    </div>

	<script>
        $('#connectWoo').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var data = form.serialize();
            var url = form.attr('action');
            console.log(url);
            
            addMaskLoading('{{ trans('messages.source.woo.connecting') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        wooPopup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // notify
                    window.location = res.redirect;
                }
            });    
        });
	</script>
@endsection
