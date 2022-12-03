@extends('layouts.core.install')

@section('title', trans('messages.database'))

@section('content')

<h3 class="text-primary"><span class="material-symbols-rounded">
dns
</span> {{ trans('messages.database_configuration') }}</h3>

    <p class="">
        The settings was successfully configured! Click <span class="fw-600">{!! trans('messages.setup_database') !!}</span> button to start importing data to database '{{ $database["database_name"] }}'.
    </p>

	@if ($tables_exist)
		<div class="alert alert-danger">
			Application is going to initialize your database, all existing data will be erased
		</div>
	@endif

    <div class="text-end">
        <a href="{{ action('InstallController@database') }}" class="btn btn-secondary me-1"><span class="material-symbols-rounded">
undo
</span> {!! trans('messages.back') !!}</a>
        <a href="{{ action('InstallController@import') }}" class="btn btn-primary db-setup">{!! trans('messages.setup_database') !!} <span class="material-symbols-rounded">
east
</span></a>
		
    </div>

    <script>

        $(function() {
            $('.db-setup').on('click', function() {
                addButtonMask($(this));
            });
        });

    </script>

@endsection
