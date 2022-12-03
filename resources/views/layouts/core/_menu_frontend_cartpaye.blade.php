@php
    $wordpress = new \Acelle\Library\WordpressManager();
@endphp
<nav class="navbar navbar-expand-xl navbar-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <span class="default-app-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 389.3 60.1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><path d="M38.5,56.4,36.7,43.8H16.9l-7,12.6H0L29.6,6h9.8l8,50.4ZM33.1,16V13.6h-.2a18,18,0,0,1-1.6,3.8L20.6,36.7H35.7l-2.4-19A9.9,9.9,0,0,1,33.1,16Z" style="fill:#fff"/><path d="M82.7,28.9A13.5,13.5,0,0,0,79.4,27a13.2,13.2,0,0,0-4.4-.8,10.4,10.4,0,0,0-6.1,2,14.7,14.7,0,0,0-4.5,5.7,17.5,17.5,0,0,0-1.8,7.8c0,2.9.7,5.1,2,6.6a7,7,0,0,0,5.6,2.3,12.2,12.2,0,0,0,4.6-.9,22.6,22.6,0,0,0,4.4-2.2l-1.5,7.2a22.4,22.4,0,0,1-9.9,2.6c-4.3,0-7.6-1.3-10.1-3.9s-3.6-6.3-3.6-10.9a26,26,0,0,1,2.7-11.6,19.9,19.9,0,0,1,7.6-8.4,20.2,20.2,0,0,1,10.9-3,22.2,22.2,0,0,1,5.1.6,19.7,19.7,0,0,1,4,1.6Z" style="fill:#fff"/><path d="M118.6,29.3a10,10,0,0,1-6,9.5c-4.1,2.1-10.2,3.2-18.4,3.3v1.1a7.4,7.4,0,0,0,2.2,5.6,8.6,8.6,0,0,0,6.1,2.1,22,22,0,0,0,5.7-1,39.9,39.9,0,0,0,5.6-2.5l-1.4,7a25,25,0,0,1-11.7,2.9c-4.7,0-8.3-1.3-10.9-3.9s-4-6.3-4-11a25.6,25.6,0,0,1,2.7-11.5A20.7,20.7,0,0,1,96,22.6a18.4,18.4,0,0,1,10.4-3.1q5.7,0,9,2.7A8.8,8.8,0,0,1,118.6,29.3Zm-8.2.2a3.7,3.7,0,0,0-1.3-2.8,4.9,4.9,0,0,0-3.5-1.1,9.8,9.8,0,0,0-6.8,3.1,15.7,15.7,0,0,0-4,7.5c10.4,0,15.6-2.2,15.6-6.7Z" style="fill:#fff"/><path d="M130.6,57c-2.6,0-4.6-.6-6-1.9a7.5,7.5,0,0,1-2-5.5,60.1,60.1,0,0,1,1.3-8.5c.9-4.1,3.6-16.8,8.1-38h8.6l-8.5,40a35.4,35.4,0,0,0-.8,4.5c0,1.8,1,2.7,3.1,2.7a8.7,8.7,0,0,0,3.2-.6l-1.3,6.5A22.4,22.4,0,0,1,130.6,57Z" style="fill:#fff"/><path d="M151.3,57c-2.6,0-4.6-.6-5.9-1.9a7.1,7.1,0,0,1-2-5.5,48.5,48.5,0,0,1,1.3-8.5c.9-4.1,3.6-16.8,8.1-38h8.5l-8.5,40a23.4,23.4,0,0,0-.7,4.5q0,2.7,3,2.7a8.7,8.7,0,0,0,3.2-.6L157,56.2A22.4,22.4,0,0,1,151.3,57Z" style="fill:#fff"/><path d="M196.3,29.3a10,10,0,0,1-6,9.5c-4,2.1-10.2,3.2-18.4,3.3v1.1a7.3,7.3,0,0,0,2.1,5.6,8.6,8.6,0,0,0,6.1,2.1,22,22,0,0,0,5.7-1,28.1,28.1,0,0,0,5.6-2.5l-1.4,7a25,25,0,0,1-11.7,2.9c-4.7,0-8.3-1.3-10.9-3.9s-3.9-6.3-3.9-11a26.9,26.9,0,0,1,2.6-11.5,20.7,20.7,0,0,1,7.5-8.3,18.7,18.7,0,0,1,10.5-3.1c3.7,0,6.7.9,8.9,2.7A8.5,8.5,0,0,1,196.3,29.3Zm-8.2.2a3.2,3.2,0,0,0-1.3-2.8,4.9,4.9,0,0,0-3.5-1.1,9.8,9.8,0,0,0-6.8,3.1,14.7,14.7,0,0,0-3.9,7.5C182.9,36.2,188.1,34,188.1,29.5Z" style="fill:#fff"/><path d="M339.6,59.2h-8.7a17.3,17.3,0,0,1,.3-3.2,22,22,0,0,1,.4-3.6h-.2a28.9,28.9,0,0,1-3.8,4.7,12.4,12.4,0,0,1-3.4,2.2,12.6,12.6,0,0,1-4.3.8,9.1,9.1,0,0,1-7.9-3.7c-1.9-2.4-2.9-5.7-2.9-10A25.6,25.6,0,0,1,312.2,34a19.9,19.9,0,0,1,8.2-8.8,23.9,23.9,0,0,1,12-2.9A68.3,68.3,0,0,1,345.9,24l-5,23.5c-.3,1.6-.6,3.7-.9,6.1A52.7,52.7,0,0,0,339.6,59.2Zm-3.3-30a15.7,15.7,0,0,0-4.8-.5,12.8,12.8,0,0,0-7.1,2.1,14.4,14.4,0,0,0-4.9,6.3,22.5,22.5,0,0,0-1.8,8.9,9.2,9.2,0,0,0,1.3,5.4,4.5,4.5,0,0,0,4,1.9c2.5,0,4.8-1.3,6.8-3.9a26.4,26.4,0,0,0,4.4-10.5Z" style="fill:#fff"/><path d="M358.6,59.8a8.3,8.3,0,0,1-5.9-2,7.1,7.1,0,0,1-2-5.5,14.7,14.7,0,0,1,.4-3.6l5.2-25.5h8.5l-4.7,22.7a35.4,35.4,0,0,0-.8,4.5c0,1.8,1,2.7,3.1,2.7a8.7,8.7,0,0,0,3.2-.6L364.2,59A21,21,0,0,1,358.6,59.8ZM368.1,11a4.7,4.7,0,0,1-1.5,3.5,5.5,5.5,0,0,1-3.7,1.4,4.5,4.5,0,0,1-3.5-1.3,3.9,3.9,0,0,1-1.5-3.3,3.9,3.9,0,0,1,1.6-3.5,5,5,0,0,1,3.7-1.3,5.5,5.5,0,0,1,3.5,1.2A4.7,4.7,0,0,1,368.1,11Z" style="fill:#fff"/><path d="M379.3,59.8a8.3,8.3,0,0,1-5.9-2,6.9,6.9,0,0,1-2.1-5.4,48.6,48.6,0,0,1,1.4-8.5c.9-4.1,3.6-16.8,8.1-38h8.5l-8.5,40a23.4,23.4,0,0,0-.7,4.5q0,2.7,3,2.7a8.7,8.7,0,0,0,3.2-.6L385,59A22.4,22.4,0,0,1,379.3,59.8Z" style="fill:#fff"/><path d="M307.4.1,310,3.3c-.1.4-.1.7-.2,1.1l-.2.6L297.9,59.1H284.5l10.4-44L266.1,44.8l-4.2-.6L246.7,16.9c-3.6,14-7.1,28-10.7,42.1l-11.6.2,14-54.8c.3-1.5.7-2.9,1.5-3.4h10.2c-.3-.8-.2-.6-.1-.4s1.3,2.5,1.9,3.8l.4.6c4.5,8.9,9.1,17.7,13.7,26.5L291.7,5l.6-.6L296.5.1Z" style="fill:#fff"/><path d="M310,3.5a2.9,2.9,0,0,0-.2.9H238.4l.4-1.8A3.4,3.4,0,0,1,242.1,0h65.1a2.9,2.9,0,0,1,2.9,2.9C310.1,3.1,310,3.3,310,3.5Z" style="fill:#fff"/><path d="M228.9,14.7H203.3a2.5,2.5,0,0,1,0-5h25.6a2.5,2.5,0,0,1,0,5Z" style="fill:#fff"/><path d="M225.3,28.7H213.5a2.5,2.5,0,0,1,0-5h11.8a2.5,2.5,0,0,1,0,5Z" style="fill:#fff"/><path d="M221.9,42.7h-3.1a2.5,2.5,0,0,1,0-5h3.1a2.5,2.5,0,0,1,0,5Z" style="fill:#fff"/></g></g></g></g></svg>
                </span>
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="leftbar-hide-menu middle-bar-element">
            <svg class="SideBurgerIcon-image" viewBox="0 0 50 32"><path d="M49,4H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,4,49,4z"></path><path d="M49,16H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,16,49,16z"></path><path d="M49,28H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,28,49,28z"></path><path d="M8.1,22.8c-0.3,0-0.5-0.1-0.7-0.3L0.7,15l6.7-7.8c0.4-0.4,1-0.5,1.4-0.1c0.4,0.4,0.5,1,0.1,1.4L3.3,15l5.5,6.2   c0.4,0.4,0.3,1-0.1,1.4C8.6,22.7,8.4,22.8,8.1,22.8z"></path></svg>
        </span>

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-md-0 main-menu">
                <li class="nav-item" rel0="HomeController">
                    <a href="{{ action('HomeController@index') }}" title="{{ trans('messages.dashboard') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92.1 86.1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><path d="M51.8,86.1H41.9a8.5,8.5,0,0,1-8.5-8.5V60.2a8.5,8.5,0,0,1,8.5-8.5h9.9a8.5,8.5,0,0,1,8.5,8.5V77.6A8.5,8.5,0,0,1,51.8,86.1ZM41.9,58.7a1.5,1.5,0,0,0-1.5,1.5V77.6a1.5,1.5,0,0,0,1.5,1.5h9.9a1.5,1.5,0,0,0,1.5-1.5V60.2a1.5,1.5,0,0,0-1.5-1.5Z" style="fill:aqua"/><path d="M60.4,86.1H31.7A20.6,20.6,0,0,1,11.2,65.7V24.6h7V65.7A13.5,13.5,0,0,0,31.7,79.1H60.4A13.5,13.5,0,0,0,73.9,65.7V25.3h7V65.7A20.6,20.6,0,0,1,60.4,86.1Z" style="fill:#f2f2f2"/><path d="M88.6,36.5a3.6,3.6,0,0,1-2-.6L45.7,7.7,5.5,35.1a3.5,3.5,0,1,1-4-5.8L43.7.6a3.6,3.6,0,0,1,4,0L90.6,30.1a3.5,3.5,0,0,1-2,6.4Z" style="fill:#f2f2f2"/></g></g></g></g></svg>
                        </i>
                        <span>{{ trans('messages.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="" title="{{ trans('messages.content') }}"
                        class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle"
                        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="navbar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88.2 71.4"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><rect x="18.4" y="47.9" width="52.5" height="10.1" style="fill:#f2f2f2"/><rect x="18.4" y="30.7" width="52.5" height="10.1" style="fill:#ffbfbf"/><path d="M71.3,71.4h0l-53.6-.5a2.8,2.8,0,0,1-1.7-.7,2.2,2.2,0,0,1-.7-1.7l.9-38-4.4,2.7a2.2,2.2,0,0,1-1.9.3,2.6,2.6,0,0,1-1.5-1.2C5.7,26.8,3,21.3.2,15.8a2.4,2.4,0,0,1,.3-2.6A39.6,39.6,0,0,1,16.8,1.8C21.5.2,24.5.3,30.4.5L42.6.7C48.8.6,53,.4,56.1.2c5.3-.3,8-.5,12.5.8a42.7,42.7,0,0,1,19,11.9,2.5,2.5,0,0,1,.4,2.6L80.6,31.9A2.3,2.3,0,0,1,77.3,33l-4.7-2.7L73.7,69a2.2,2.2,0,0,1-.7,1.7A2.4,2.4,0,0,1,71.3,71.4ZM20.1,66.1l48.7.5L67.6,26.1A2.2,2.2,0,0,1,68.8,24a2.3,2.3,0,0,1,2.4,0l6.1,3.5L83,14.9A37.8,37.8,0,0,0,67.3,5.5c-3.7-1-5.8-.9-10.9-.6-3.1.2-7.4.4-13.7.5L30.3,5.2c-5.8-.2-8-.3-11.8,1.1A34.8,34.8,0,0,0,5.2,15.1l6.3,12.7,5.9-3.7a2.1,2.1,0,0,1,2.4,0A2.4,2.4,0,0,1,21,26.2Z" style="fill:#f2f2f2"/><path d="M44.4,17.9c-3.5,0-6.9-1.6-10-4.8a16.7,16.7,0,0,1-4.5-10,2.4,2.4,0,1,1,4.7-.5,12.5,12.5,0,0,0,3.2,7.1c.9.9,3.5,3.6,7,3.3s6.4-3.9,7.5-6.1A16.6,16.6,0,0,0,53.9,2a2.4,2.4,0,0,1,4.7.7A18,18,0,0,1,56.5,9c-2.7,5.3-6.8,8.4-11.4,8.7A.8.8,0,0,1,44.4,17.9Z" style="fill:#f2f2f2"/></g></g></g></g></svg>
                        </i>
                        <span>{{ trans('messages.brand.product') }}</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="content-menu">
                        <li class="nav-item" rel0="ProductController/index">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\ProductController@index') }}">
                                <span>{{ trans('messages.brand.products') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="ProductController/index2">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\ProductController@index2') }}">
                                <span>{{ trans('messages.brand.products') }} (layout 2)</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="CategoryController">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\CategoryController@index') }}">
                                <span>{{ trans('messages.brand.categories') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" rel0="OrderController">
                    <a href="{{ action('Site\OrderController@index') }}" title="{{ trans('messages.products') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 106.1 107.7"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><g id="Layer_2-2-2" data-name="Layer 2-2"><g id="Layer_1-2-2-2" data-name="Layer 1-2-2"><path d="M26.5,107.7A26.6,26.6,0,0,1,0,81.4v-55A26.6,26.6,0,0,1,26.5,0H62.8a3.5,3.5,0,0,1,3.5,3.5A3.5,3.5,0,0,1,62.8,7H26.5A19.5,19.5,0,0,0,7,26.4v55a19.5,19.5,0,0,0,19.5,19.3H76.6A19.5,19.5,0,0,0,96,81.3V55.4a3.5,3.5,0,0,1,7,0V81.3a26.6,26.6,0,0,1-26.4,26.4Z" style="fill:#f2f2f2"/><path d="M51.5,55.3A16.8,16.8,0,1,1,68.3,38.5,16.8,16.8,0,0,1,51.5,55.3Zm0-26.6a9.8,9.8,0,1,0,9.8,9.8A9.8,9.8,0,0,0,51.5,28.7Z" style="fill:#ffadad"/><path d="M77.9,71.7H25.1a3.5,3.5,0,0,1,0-7H77.9a3.5,3.5,0,0,1,0,7Z" style="fill:#f2f2f2"/><path d="M77.9,86H25.1a3.5,3.5,0,1,1,0-7H77.9a3.5,3.5,0,0,1,0,7Z" style="fill:#f2f2f2"/><path d="M97.1,40.9a2,2,0,0,1-1.1-.3l-9.6-5-9.5,5a2.3,2.3,0,0,1-2.5-.2,2.5,2.5,0,0,1-1-2.3l1.8-10.7-7.7-7.5a2.4,2.4,0,0,1-.6-2.4,2.7,2.7,0,0,1,2-1.7l10.6-1.5,4.7-9.7a2.6,2.6,0,0,1,2.2-1.3h0a2.3,2.3,0,0,1,2.1,1.3l4.9,9.7L104,15.8a2.7,2.7,0,0,1,2,1.7,2.4,2.4,0,0,1-.6,2.4l-7.7,7.5,1.8,10.7a2.5,2.5,0,0,1-1,2.3A2.4,2.4,0,0,1,97.1,40.9ZM86.4,30.5a2,2,0,0,1,1.1.3l6.4,3.3L92.7,27a2.6,2.6,0,0,1,.7-2.1l5.1-5-7-1a2.3,2.3,0,0,1-1.8-1.3l-3.3-6.5-3.2,6.5a2.3,2.3,0,0,1-1.8,1.3l-7,1,5.1,5a2.6,2.6,0,0,1,.7,2.1L79,34.1l6.3-3.3A2,2,0,0,1,86.4,30.5Zm-5.3-14Z" style="fill:#f2f2f2"/></g></g></g></g></g></g></svg>
                        </i>
                        <span>{{ trans('messages.brand.orders') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="CustomerController">
                    <a href="{{ action('Site\CustomerController@index') }}" title="{{ trans('messages.products') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 103"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><path d="M46,51.6A25.8,25.8,0,1,1,71.8,25.8,25.9,25.9,0,0,1,46,51.6ZM46,7A18.8,18.8,0,1,0,64.8,25.8,18.8,18.8,0,0,0,46,7Z" style="fill:#f2f2f2"/><path d="M88.5,103A3.5,3.5,0,0,1,85,99.5a39,39,0,0,0-78,0A3.5,3.5,0,0,1,3.5,103,3.5,3.5,0,0,1,0,99.5a46,46,0,0,1,92,0A3.5,3.5,0,0,1,88.5,103Z" style="fill:#f2f2f2"/><path d="M19.5,103H3.5a3.5,3.5,0,0,1,0-7h16a3.5,3.5,0,0,1,0,7Z" style="fill:#f2f2f2"/><path d="M88.5,103H36.9a3.5,3.5,0,0,1,0-7H88.5a3.5,3.5,0,0,1,0,7Z" style="fill:#f2f2f2"/><path d="M46,39c-3.3,0-6.4-1.6-7.7-4a3.6,3.6,0,0,1,1.4-4.8,3.5,3.5,0,0,1,4.7,1.4A3.5,3.5,0,0,0,46,32a3,3,0,0,0,1.6-.5,3.4,3.4,0,0,1,4.5-1.6,3.4,3.4,0,0,1,1.8,4.6C52.6,37.6,48.9,39,46,39Zm-1.5-7.4Z" style="fill:lime"/></g></g></g></g></svg>
                        </i>
                        <span>{{ trans('messages.customers') }}</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="" title="{{ trans('messages.content') }}"
                        class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle"
                        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="navbar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 94.4 85.6"><defs><style>.cls-1{fill:#93c2a0;}.cls-2{fill:#f2f2f2;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><path class="cls-1" d="M37,58.5H55.1a4.23,4.23,0,0,1,4.2,4.2V78a4.23,4.23,0,0,1-4.2,4.2H37A4.23,4.23,0,0,1,32.8,78V62.7A4.23,4.23,0,0,1,37,58.5Z"/><path class="cls-2" d="M94.4,32.8a17.74,17.74,0,0,0-.9-5.6h0c0-.3,0-.3-.3-.6l-8-19.8A10.37,10.37,0,0,0,75.2,0h-56c-4.7,0-8.3,2.4-9.7,6.5L.9,26.8v.6A19.64,19.64,0,0,0,0,33.3,18,18,0,0,0,5.9,46.9V76.7a9,9,0,0,0,8.9,8.9h64a9,9,0,0,0,8.9-8.9V48.1c4-4.4,6.7-9.7,6.7-15.3ZM78.8,77.9h-64A1.83,1.83,0,0,1,13,76.3V50.5a21.63,21.63,0,0,0,6.2.9,17.57,17.57,0,0,0,14.2-6.8,18.35,18.35,0,0,0,14.2,6.5,17.57,17.57,0,0,0,14.2-6.8,19.6,19.6,0,0,0,14.5,6.8,18.35,18.35,0,0,0,4.7-.6V75.9a2.82,2.82,0,0,1-2.2,2Zm2.6-34.8a10.91,10.91,0,0,1-5.6,1.2,11.62,11.62,0,0,1-10-5.6c-.3-.3-.3-.9-.9-1.5a4.41,4.41,0,0,0-3.2-1.5,3.56,3.56,0,0,0-3.2,1.5,3.77,3.77,0,0,0-.9,1.5,11.84,11.84,0,0,1-16.2,3.9,12.11,12.11,0,0,1-3.9-3.9c-.3-.3-.3-.9-.9-1.2-1.5-1.8-5.3-1.8-6.5,0a2.9,2.9,0,0,0-.9,1.5,11.4,11.4,0,0,1-10,5.6,10.91,10.91,0,0,1-5.6-1.2h0A12,12,0,0,1,7.4,32.8,12.09,12.09,0,0,1,8,29v-.3L16.5,8.6c.3-.6.6-2.1,3.2-2.1H75.8a3.3,3.3,0,0,1,3.5,2.1l8,19.8v.3a25,25,0,0,1,.6,3.8,12.56,12.56,0,0,1-6.5,10.6Z"/><path class="cls-2" d="M67.1,27.3H26.7a4,4,0,0,1,0-8H67.1a4,4,0,0,1,0,8Z"/></g></g></g></g></svg>
                        </i>
                        <span>{{ trans('messages.brand.store') }}</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="content-menu">
                        <li class="nav-item" rel0="SettingController/shop">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@shop') }}">
                                {{-- <i class="navbar-icon" style="">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 94.4 85.6" style="enable-background:new 0 0 94.4 85.6;" xml:space="preserve"><style type="text/css">.st0{fill:#93C2A0;}.st1{fill:#414042;}.st2{fill:#877083;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M37,58.5h18.1c2.3,0,4.2,1.9,4.2,4.2V78c0,2.3-1.9,4.2-4.2,4.2H37c-2.3,0-4.2-1.9-4.2-4.2V62.7 C32.8,60.4,34.7,58.5,37,58.5z"/><path class="st1" d="M94.4,32.8c0-1.9-0.3-3.8-0.9-5.6l0,0c0-0.3,0-0.3-0.3-0.6l-8-19.8c-1.5-4.2-5.6-6.9-10-6.8H19.2 c-4.7,0-8.3,2.4-9.7,6.5L0.9,26.8v0.6C0.3,29.3,0,31.3,0,33.3c-0.1,5.2,2.1,10.1,5.9,13.6v29.8c0,4.9,4,8.8,8.9,8.9h64 c4.9,0,8.8-4,8.9-8.9V48.1C91.7,43.7,94.4,38.4,94.4,32.8L94.4,32.8z M78.8,77.9h-64c-0.9,0-1.7-0.7-1.8-1.6c0-0.1,0-0.1,0-0.2 V50.5c2,0.6,4.1,0.9,6.2,0.9c5.5,0.1,10.8-2.4,14.2-6.8c3.5,4.2,8.7,6.5,14.2,6.5c5.5,0.1,10.8-2.4,14.2-6.8 c3.6,4.2,8.9,6.7,14.5,6.8c1.6,0,3.2-0.2,4.7-0.6v25.4C80.7,76.9,79.9,77.7,78.8,77.9L78.8,77.9z M81.4,43.1 c-1.7,0.9-3.7,1.3-5.6,1.2c-4.1,0-7.9-2.1-10-5.6c-0.3-0.3-0.3-0.9-0.9-1.5c-0.8-0.9-2-1.5-3.2-1.5c-1.3-0.1-2.5,0.5-3.2,1.5 c-0.4,0.4-0.7,0.9-0.9,1.5c-3.4,5.5-10.7,7.3-16.2,3.9c-1.6-1-2.9-2.3-3.9-3.9c-0.3-0.3-0.3-0.9-0.9-1.2c-1.5-1.8-5.3-1.8-6.5,0 c-0.4,0.4-0.8,0.9-0.9,1.5c-2.1,3.5-5.9,5.7-10,5.6c-1.9,0.1-3.9-0.3-5.6-1.2l0,0c-3.9-2.1-6.2-6.2-6.2-10.6 c0-1.3,0.2-2.6,0.6-3.8v-0.3l8.5-20.1c0.3-0.6,0.6-2.1,3.2-2.1h56.1c1.5-0.2,3,0.7,3.5,2.1l8,19.8v0.3c0.3,1.3,0.5,2.5,0.6,3.8 C87.8,36.9,85.3,40.9,81.4,43.1L81.4,43.1z"/><path class="st2" d="M67.1,27.3H26.7c-2.2,0-4-1.8-4-4s1.8-4,4-4h40.4c2.2,0,4,1.8,4,4S69.3,27.3,67.1,27.3z"/></g></g></svg>
                                </i> --}}
                                <span>{{ trans('messages.brand.shop_info') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SettingController/products">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@products') }}">
                                <span>{{ trans('messages.brand.product_settings') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SettingController/shipping">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@shipping') }}">
                                <span>{{ trans('messages.brand.shipping') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SettingController/payments">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@payments') }}">
                                <span>{{ trans('messages.brand.payments') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SettingController/account">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@account') }}">
                                <span>{{ trans('messages.brand.account_privacy') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SettingController/emails">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('Site\SettingController@emails') }}">
                                <span>{{ trans('messages.brand.emails') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-right">
                <ul class="navbar-nav me-auto mb-md-0">
                    @include('layouts.core._top_activity_log')
                    
                    @include('layouts.core._menu_frontend_user')
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    var MenuFrontend = {
        saveLeftbarState: function(state) {
            var url = '{{ action('AccountController@leftbarState') }}';

            $.ajax({
                method: "GET",
                url: url,
                data: {
                    _token: CSRF_TOKEN,
                    state: state,
                }
            });
        }
    };

    $(function() {
        //
        $('.leftbar .leftbar-hide-menu').on('click', function(e) {
            if (!$('.leftbar').hasClass('leftbar-closed')) {
                $('.leftbar').addClass('leftbar-closed');

                $('.leftbar').removeClass('state-open');
                $('.leftbar').addClass('state-closed');

                MenuFrontend.saveLeftbarState('closed');
            } else {
                $('.leftbar').removeClass('leftbar-closed');

                $('.leftbar').removeClass('state-closed');
                $('.leftbar').addClass('state-open');

                MenuFrontend.saveLeftbarState('open');
            }
        });
    });        
</script>
