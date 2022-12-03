<nav class="navbar navbar-expand-xl nav-menu-dark navbar-dark bg-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('Admin\HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <img style="height: 18px" class="logo" src="{{ URL::asset('images/logo_light_blue.svg') }}" alt="">
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-md-0">
                <li class="nav-item" rel0="HomeController">
                    @yield('menu_title')
                </li>
            </ul>
            <div class="navbar-right">
                <ul class="navbar-nav me-auto mb-md-0">
                    @yield('menu_right')
                </ul>
            </div>
        </div>
    </div>
</nav>