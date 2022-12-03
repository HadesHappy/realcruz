<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')
</head>

<body class="bg-slate-800">

	<!-- Page container -->
	<div class="container page-container login-container pb-5" style="min-height: calc(100vh - 120px)">

		<!-- Page content -->
		<div class="page-content">

			@yield('content')

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="footer text-white d-block text-center mb-3 small text-muted2 pt-4" style="width:100%">
			{!! trans('messages.copy_right_light') !!}			
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

</body>
</html>
