<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')
</head>
<body class="layout-dark topbar">
	@include('layouts.core._menu_dark_backend')
	@include('layouts.core._middle_bar')

    @yield('page_header')

    <!-- display flash message -->
    @include('layouts.core._errors')

    <!-- main inner content -->
    @yield('content')

	<!-- Notification -->
	@include('layouts.core._notify')

	<!-- display flash message -->
	@include('layouts.core._flash')

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>