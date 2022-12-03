<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts.core._favicon')  

	@include('layouts._head')

	@include('layouts._css')

	@include('layouts._js')
	
	<!-- Custom langue -->
	<script>
		var LANG_CODE = 'en-US';
	</script>
	@if (Auth::user()->customer->getLanguageCodeFull())
		<script type="text/javascript" src="{{ URL::asset('assets/datepicker/i18n/datepicker.' . Auth::user()->customer->getLanguageCodeFull() . '.js') }}"></script>
		<script>
			LANG_CODE = '{{ Auth::user()->customer->getLanguageCodeFull() }}';
		</script>
	@endif
</head>

<body class="navbar-top color-scheme-{{ Auth::user()->customer->getColorScheme() }}">

	<header class="automation-header">
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand left-logo" href="#">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
					<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
				@else
					<img height="22" src="{{ URL::asset('images/logo_light_blue.svg') }}" alt="">
				@endif
			</a>
			<div class="d-inline-block d-flex mr-auto align-items-center">
				<h1 class="">{{ $automation->name }}</h1>
				<i class="material-symbols-rounded automation-head-icon ml-2">alarm</i>
			</div>
			<div class="automation-top-menu">
				{{-- <span class="mr-3 last_save_time"><i>{{ trans('messages.automation.designer.last_saved', ['time' => $automation->updated_at->diffForHumans()]) }}</i></span> --}}
				<a href="{{ action('Automation2Controller@index') }}" class="action">
					<i class="material-symbols-rounded me-2">arrow_back</i>
					{{ trans('messages.automation.go_back') }}
				</a>

				<div class="switch-automation d-flex">
					<select class="select select2 top-menu-select" name="switch_automation">
						@foreach($automation->getSwitchAutomations(Auth::user()->customer)->get() as $auto)
							<option value='{{ action('Automation2Controller@edit', $auto->uid) }}'>{{ $auto->name }}</option>
						@endforeach
					</select>

					<a href="javascript:'" class="action">
						<i class="material-symbols-rounded me-2">
	horizontal_split
	</i>
						{{ trans('messages.automation.switch_automation') }}
					</a>
				</div>

				<div class="account-info">
					<ul class="navbar-nav mr-auto navbar-dark bg-dark"">						
						<li class="nav-item dropdown">
							<a class="account-item nav-link dropdown-toggle px-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img class="avatar" src="{{ Auth::user()->getProfileImageUrl() }}" alt="">
								{{ Auth::user()->displayName() }}
							</a>
							<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
								@can("admin_access", Auth::user())
									<a class="dropdown-item d-flex align-items-center" href="{{ action("Admin\HomeController@index") }}">
										<i class="material-symbols-rounded me-2">double_arrow</i>
										{{ trans('messages.admin_view') }}
									</a>
									<div class="dropdown-divider"></div>
								@endif
								<a class="dropdown-item d-flex align-items-center quota-view" href="{{ action("AccountController@quotaLog2") }}">
									<i class="material-symbols-rounded me-2">multiline_chart</i>
									<span class="">{{ trans('messages.used_quota') }}</span>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="{{ action('SubscriptionController@index') }}">
									<i class="material-symbols-rounded me-2">redeem</i>
									<span>{{ trans('messages.subscriptions') }}</span>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="{{ action("AccountController@profile") }}">
									<i class="material-symbols-rounded me-2">account_circle</i>
									<span>{{ trans('messages.account') }}</span>
								</a>
								@if (Auth::user()->customer->canUseApi())
									<a href="{{ action("AccountController@api") }}" class="dropdown-item d-flex align-items-center">
										<i class="material-symbols-rounded me-2">code</i>
										<span>{{ trans('messages.campaign_api') }}</span>
									</a>
								@endif
								<div class="dropdown-divider"></div>
								<a class="dropdown-item d-flex align-items-center" href="{{ url("/logout") }}">
									<i class="material-symbols-rounded me-2">power_settings_new</i>
									<span>{{ trans('messages.logout') }}</span>
								</a>
							</div>
						</li>
					</ul>
					
				</div>
			</div>
		</nav>
	</header>

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">

			@yield('page_header')

		</div>
	</div>
	<!-- /page header -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- display flash message -->
				@include('common.errors')

				<!-- main inner content -->
				@yield('content')

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="footer text-muted">
			{!! trans('messages.copy_right') !!}
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

	@include("layouts._modals")

        {!! \Acelle\Model\Setting::get('custom_script') !!}

</body>
</html>
