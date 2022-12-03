<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
	
	@include('layouts.core._head')
	@include('layouts.core._script_vars')

	@yield('head')
</head>

<body class="list-page bg-slate-800 color-scheme-{{ isset($list) && is_object($list) ? $list->customer->getColorScheme() : '' }}">

	@yield('content')
	
</body>
</html>
