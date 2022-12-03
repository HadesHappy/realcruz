<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')
</head>
<body class="layout-dark topbar">
	@include('layouts.core._menu_dark_frontend')
	@include('layouts.core._middle_bar')

    @yield('page_header')

    <!-- main inner content -->
    @yield('content')

	<!-- Notification -->
	@include('layouts.core._notify')
	@include('layouts.core._notify_frontend')

	<!-- display flash message -->
	@include('layouts.core._flash')

	<!-- Admin area -->
	@include('layouts.core._admin_area')

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>