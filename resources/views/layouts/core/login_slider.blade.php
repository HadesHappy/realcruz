<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Google icon -->
        <link href="{{ URL::asset('core/css/google-font-icon.css') }}?v=2" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">

        <link rel="stylesheet" href="{{ URL::asset('core/ux/css/general.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('core/ux/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('core/ux/css/app.css') }}?v={{ app_version() }}">
    </head>
    <body>
            <!-- main inner content -->
            @yield('content')
    </body>
</html>
