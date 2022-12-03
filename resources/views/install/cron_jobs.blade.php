@extends('layouts.core.install')

@section('title', trans('messages.cron_jobs'))

@section('content')
	<form action="{{ action('InstallController@cronJobs') }}" method="POST" class="form-validate-jqueryz">
		{!! csrf_field() !!}
		
		@include('elements._cron_jobs')
    
		<hr>
		<div class="text-end">
			@if($valid)
				<a href="{{ action('InstallController@cronJobs') }}" class="btn btn-secondary">
					<span class="material-symbols-rounded">
settings
</span> {!! trans('messages.change_cronjob_setting') !!}
				</a>
				<a href="{{ action('InstallController@finishing') }}" class="btn btn-primary">
					{!! trans('messages.next') !!} <span class="material-symbols-rounded">
east
</span>
				</a>				
			@else
				<button type="submit" class="btn btn-primary bg-teal save-button">
					{!! trans('messages.check_and_save_crontab') !!}
				</button>
			@endif
		</div>

		<script>

			$(function() {
				$('.save-button').on('click', function() {
					addButtonMask($(this));
				});
			});

		</script>
	</form>
@endsection
