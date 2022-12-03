@extends('layouts.core.install')

@section('title', trans('messages.requirement'))

@section('content')

	<h3 class="text-primary mb-4"><i class="icon-puzzle2"></i> {{ trans('messages.requirements') }}</h3>

    <div class="row">
        <div class="col-md-12">
            <ul class="modern-listing mt-0">
                @foreach ($compatibilities as $key => $item)
					@if ($item["type"] == "requirement")
						<li class="d-flex align-items-center">
							@if ($item["check"])
								<span class="material-symbols-rounded fs-3 text-success me-3">
task_alt
</span>
							@else
								<span class="material-symbols-rounded fs-3 text-danger me-3">
error_outline
</span>
							@endif
							<div class="ml-0">
								<h5 class="mt-0 mb-1 fw-600">
									{{ $item["name"] }}
								</h5>
								<p>
									{{ $item["note"] }}
								</p>
							</div>
						</li>
					@endif
                @endforeach
            </ul>
        </div>
    </div>
		
	<h3 class="text-primary mb-4 mt-4"><i class="icon-file-check"></i> {{ trans('messages.permissions') }}</h3>

    <div class="row">
        <div class="col-md-12">
            <ul class="modern-listing mt-0">
                @foreach ($compatibilities as $key => $item)
					@if ($item["type"] == "permission")
						<li class="d-flex align-items-center">
							@if ($item["check"])
								<span class="material-symbols-rounded fs-3 text-success me-3">
task_alt
</span>
							@else
								<span class="material-symbols-rounded fs-3 text-danger me-3">
error_outline
</span>
							@endif
							<div class="ml-0">
								<h5 class="mt-0 mb-1 fw-600">
									{{ $item["name"] }}
								</h5>
								<p>
									{{ $item["note"] }}
								</p>
							</div>
						</li>
					@endif
                @endforeach
            </ul>
        </div>
    </div>
	
	<div class="text-end">                                    
		@if ($result)
			<a href="{{ action('InstallController@siteInfo') }}" class="btn btn-primary bg-teal btn-effect save-button">
				{!! trans('messages.next') !!} <span class="material-symbols-rounded">
east
</span></a>
		@else
			<a href="{{ action('InstallController@systemCompatibility') }}" class="btn btn-primary bg-grey-600 save-button">
				<i class="icon-reload-alt position-right"></i> {!! trans('messages.try_again') !!}</a>
		@endif
	</div>

	<script>

		$(function() {
			$('.save-button').on('click', function() {
				addButtonMask($(this));
			});
		});

	</script>

@endsection
