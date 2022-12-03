@extends('layouts.popup.large')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mb-3">{{ trans('messages.automation.automation_trigger') }}</h2>
            <p>{{ trans('messages.automation.trigger.intro') }}</p>
                
            <div class="box-list mt-3">
				<div class="box-list mt-5">
					@foreach ($types as $type)
						<a class="box-item trigger-select-but trigger-{{ $type }} shadow-sm"
							data-key="{{ $type }}"	
							href="{{ action('Automation2Controller@wizardTriggerOption', [
								'trigger_type' => $type,
							]) }}"					
						>							
							@include('automation2.trigger.icons.' . $type)
						</a>
					@endforeach                
            </div>
        </div>
    </div>

	<script>
		$(function() {
			$('.trigger-select-but').on('click', function(e) {
				e.preventDefault();
				var url = $(this).attr('href');
				
				createAutomationPopup.load(url);
			});
		});
	</script>
@endsection
