<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="php-version" content="{{ phpversion() }}" />

	@include('layouts.core._favicon_default')

	<title>@yield('title')</title>

	@include('layouts.core._includes')		

	@include('layouts.core._script_vars')
</head>

<body class="bg-slate-800">

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
					
						<div class="text-center login-header">
							<a class="main-logo-big mb-2 d-block" href="{{ action('HomeController@index') }}">
								<img width="400px" src="{{ URL::asset('images/logo_big.svg') }}" alt="">
							</a>
                            
                            <h3 class="text-center text-muted2" style="color: #ccc">{{ trans('messages.installation') }}</h3>
						</div>
                        
                        @include('install._steps')
                        
                        <div class="panel panel-flat shadow bg-white rounded-bottom-3" style="border-radius: 0 0 3px 3px">
                            <div class="panel-body">
								@if (count($errors) > 0)
									<div class="alert alert-danger alert-noborder">
										@foreach ($errors->all() as $key => $error)
											<p class="text-semibold">{{ $error }}</p>
										@endforeach
									</div>
								@endif

                                @yield('content')
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
