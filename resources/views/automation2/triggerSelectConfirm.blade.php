@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">
                {{ trans('messages.automation.trigger.' . $key) }}
            </h3>
            <p class="mb-10">
                {!! trans('messages.automation.trigger.' . $key . '.intro') !!}
			</p>
				
			<form id="trigger-select" action="{{ action("Automation2Controller@triggerSelect", $automation->uid) }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<input type="hidden" name="options[key]" value="{{ $key }}" />
				<input type="hidden" name="" value="{{ $key }}" />
				
				@if(View::exists('automation2.trigger.' . $key))
					@include('automation2.trigger.' . $key)
				@endif
				
				<button class="btn btn-secondary select-trigger-confirm mt-2"
					data-url="{{ action('Automation2Controller@triggerSelect', ['uid' => $automation->uid]) }}"
				>
					{{ trans('messages.automation.trigger.select_confirm') }}
				</button>
			</form>
        </div>
    </div>

	<script>
		function confirm() {
			var url = $('.select-trigger-confirm').attr('data-url');
			var data = $('.select-trigger-confirm').closest('form').serialize();

			// show loading effect
			popup.loading();

			$.ajax({
				url: url,
				method: 'POST',
				data: data,
				globalError: false,
				statusCode: {
					// validate error
					400: function (res) {
						popup.loadHtml(res.responseText);
					}
				},
				success: function (response) {					
					// todo: when trigger selected
					// console.log('Trigger was selected');
					
					// set node title
					tree.setTitle(response.title);
					// merge options with reponse options
					tree.setOptions(response.options);
					tree.setOptions($.extend(tree.getOptions(), {init: "true"}));

					// validate
					tree.validate();

					// FLAG for resetting trigger
					var flag = { resetTrigger: true }
					
					// save tree
					saveData(function() {
						// select trigger
						doSelect(tree);

						// hide popup
						popup.hide();
						
						// notify success message
						notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
						
						// Edit Trigger
						EditTrigger('{{ action('Automation2Controller@triggerEdit', $automation->uid) }}' + '?key=' + tree.getOptions().key);
					}, flag);
				}
			});
		}

		// when click confirm select trigger type
		$('.select-trigger-confirm').click(function(e) {
			e.preventDefault();
			
			@if ($automation->getTrigger()->getOption('init') == 'true' && $automation->getTrigger()->getOption('type') != $key)
				var dialog = new Dialog('confirm', {
					message: '{{ trans('messages.automation.trigger.change.confirm') }}',
					ok: function(dialog) {
						confirm();   
					}
				});
			@else
				confirm();
			@endif
					
		});
	</script>
@endsection
