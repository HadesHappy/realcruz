<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ $title }}</title>

	<!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Code+Pro:400,600" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/app.css') }}?v={{ app_version() }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/theme.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    
    <link href="{{ URL::asset('bootstrap3-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css">


</head>

<body class="navbar-top color-scheme-default pace-done">

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- display flash message -->
				<h1>{{ $title }}</h1>

				<!-- main inner content -->
				<p>{!! $message !!}</p>

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
