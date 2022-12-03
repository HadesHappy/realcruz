@extends('layouts.popup.medium')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">
                {{ trans('messages.automation.trigger.' . $trigger_type) }}
            </h3>
            <p class="mb-10">
                {!! trans('messages.automation.trigger.' . $trigger_type . '.intro') !!}
            </p>
                
            <form id="trigger-select" action="{{ action("Automation2Controller@wizardTriggerOption") }}"
                method="POST"
            >
                {{ csrf_field() }}
                
                <input type="hidden" name="options[key]" value="{{ $trigger_type }}" />
                <input type="hidden" name="trigger_type" value="{{ $trigger_type }}" />
                <input type="hidden" name="" value="{{ $trigger_type }}" />
                
                @if(View::exists('automation2.wizard.' . $trigger_type))
                    @include('automation2.wizard.' . $trigger_type)
                @endif

                <div class="automation-segment">

                </div>
                
                <button class="btn btn-secondary select-trigger-confirm mt-2">
                    {{ trans('messages.automation.trigger.select_confirm') }}
                </button>
            </form>
        </div>
    </div>

	<script>
		$(function() {
            // automation segment
            var automationSegment = new Box($('#trigger-select .automation-segment'));
            $('#trigger-select [name=mail_list_uid]').change(function(e) {
                var url = '{{ action('Automation2Controller@segmentSelect') }}?list_uid=' + $(this).val();

                automationSegment.load(url);
            });
            $('#trigger-select [name=mail_list_uid]').change();

            $('#trigger-select').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    createAutomationPopup.loadHtml(response);
                }).fail(function(response){
                    createAutomationPopup.loadHtml(response.responseText);
                }).always(function() {
                });
            });
		});
	</script>
@endsection
