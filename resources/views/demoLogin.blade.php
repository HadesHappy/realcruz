<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="php-version" content="{{ phpversion() }}" />

    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,600" rel="stylesheet" type="text/css">

	<title>@yield('title')</title>

	@include('layouts.core._includes')		

	@include('layouts.core._script_vars')
</head>

<body class="bg-slate-80x pt-4">

	<!-- Page container -->
	<div class="page-container login-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<div class="row">
					<div class="col-sm-1 col-md-1">
						
					</div>
					<div class="col-sm-10 col-md-10">
					
						<div class="text-center login-header mt-0">
							<a class="main-logo-big mb-2 d-block" href="{{ action('HomeController@index') }}">
								<img width="200px" src="{{ URL::asset('images/logo-dark.svg') }}" alt=""><br>
                                <img width="150px" src="{{ URL::asset('images/wave.svg') }}" alt="" style="transform: translateX(30px) translateY(-10px)">
							</a>
						</div>
                        
                        <div class="panel panel-flat" style="border-radius: 0 0 3px 3px;margin-top: 80px">
                            <div class="text-center">
								<h1 class="mb-4" style="font-family: 'Roboto Slab'; font-weight:500;font-size:48px">Logged in successfully</h1>
                                <p class="display-4" style="line-height:1.6">Thank you for giving Acelle a try. However, the Google / Facebook authentication is disabled in our demo
                                    site. Don't worry, just go back and login using the DEMO account to discover Acelle features</p>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ url('/login') }}" class="btn btn-lg btn-primary display-5 px-5 py-3" style="font-weight:600">
                                    <i class="material-symbols-rounded me-2">west</i>
                                    Back to login page
                                </a>

                                <div class="text-center mt-5">
                                    <img width="200px" style="opacity:0.9" src="{{ URL::asset('images/demo.svg') }}" alt=""><br>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="small">
			<div class="footer text-white text-center py-3" style="width: 100%">
				{!! trans('messages.copy_right_light') !!}
			</div>
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

</body>
</html>
