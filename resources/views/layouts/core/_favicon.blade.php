@if (\Acelle\Model\Setting::get('site_favicon'))
    <link rel="shortcut icon" type="image/png" href="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_favicon')) }}"/>
@else
    @include('layouts.core._favicon_default')
@endif