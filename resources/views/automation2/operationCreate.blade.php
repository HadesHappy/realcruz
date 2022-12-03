@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form id="operation-edit" action="{{ action('Automation2Controller@operationCreate', [
                'uid' => $automation->uid,
                'operation' => request()->operation
            ]) }}" method="POST" class="form-validate-jquery">
				{{ csrf_field() }}

                <input type="hidden" name="operation" value="{{ request()->operation }}" />
				
				@include('automation2.operation.' . request()->operation)
				
				<button class="btn btn-secondary select-action-confirm mt-2">
						{{ trans('messages.automation.operation.save') }}
				</button>
			</form>
        </div>
    </div>
    <script>
        $('#operation-edit').on('submit', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            if (!$(this).valid()) {
                return;
            }
            
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        // newSubscription.loadHtml(res.responseText);
                        // notify
                        // notify('error', '{{ trans('messages.notify.error') }}', res.responseText);
                    }
                },
                success: function (response) {
                    @if (!request()->operation_id)
                        var newE = new ElementOperation({title: response.title, options: response.options});

                        // Master "MyAutomation" object
                        MyAutomation.addToTree(newE);

                        newE.validate();
                        
                        // save tree
                        saveData(function() {
                            // hide popup
                            popup.hide();

                            setTimeout(function() {
                                doSelect(newE);
                            }, 200);
                            
                            notify({
                                type: 'success',
                                title: '{!! trans('messages.notify.success') !!}',
                                message: response.message
                            });
                        });
                    @else
                        tree.getSelected().setOptions(response.options);
                        tree.getSelected().setTitle(response.title);
                        
                        // save tree
                        saveData(function() {
                            popup.hide();
                            
                            // reload sidebar
                            sidebar.load();
                            
                            notify({
                                type: 'success',
                                title: '{!! trans('messages.notify.success') !!}',
                                message: response.message
                            });
                        });
                    @endif
                },
                error: function (res) {
                    // newSubscription.loadHtml(res.responseText);
                    // notify
                    alert(res.responseText);
                }
            });
        });
    </script>

@endsection
