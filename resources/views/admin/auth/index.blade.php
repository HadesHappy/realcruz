@extends('layouts.core.backend')

@section('title', trans('messages.plugins'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                admin_panel_settings
                </span> {{ trans('messages.oauth') }}</span>
		</h1>
	</div>

@endsection

@section('content')
	
	<div class="row">
		<div class="col-md-8">
			<p>{{ trans('messages.oauth.wording') }}</p>
			<table class="table table-box pml-table mt-2"
				current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
			>
				<tr>
					<td class="pe-2" width="1%">
						<img width="52px" class="" src="{{ url('images/google-login.svg') }}" />
					</td>
					<td>
						<div class="d-flex align-items-center mb-1">
							<h4 class="mb-0 me-3">Google</h4>
							@if (\Acelle\Model\Setting::get('oauth.google_enabled') == 'yes')
								<span class="label label-flat bg-active">{{ trans('messages.oauth.status.active') }}</span>
							@else
								<span class="label label-flat bg-disabled">{{ trans('messages.oauth.status.disabled') }}</span>
							@endif
						</div>
						<p class="mb-0">{{ trans('messages.google_oauth.intro') }}</p>
					</td>
					<td class="text-end text-nowrap px-4">
						<span class="text-muted2 list-status pull-left">
							
						</span>
					</td>
					<td class="text-end">
						<a href="{{ action('Admin\AuthController@googleOAuth') }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-light btn-icon text-nowrap">
							<span class="material-symbols-rounded">
							settings
							</span> {{ trans('messages.settings') }}
						</a>
					</td>
				</tr>
				<tr>
					<td class="pe-2" width="1%">
						<img width="50px" class="" src="{{ url('images/icons/facebook-logo.svg') }}" />
					</td>
					<td>
						<div class="d-flex align-items-center mb-1">
							<h4 class="mb-0 me-3">Facebook</h4>
							@if (\Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes')
								<span class="label label-flat bg-active">{{ trans('messages.oauth.status.active') }}</span>
							@else
								<span class="label label-flat bg-disabled">{{ trans('messages.oauth.status.disabled') }}</span>
							@endif
						</div>
						<p class="mb-0">{{ trans('messages.facebook_oauth.intro') }}</p>
					</td>
					<td class="text-end text-nowrap px-4">
						<span class="text-muted2 list-status pull-left">
							
						</span>
					</td>
					<td class="text-end">
						<a href="{{ action('Admin\AuthController@facebookOAuth') }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-light btn-icon text-nowrap">
							<span class="material-symbols-rounded">
							settings
							</span> {{ trans('messages.settings') }}
						</a>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<script>
    </script>
@endsection
