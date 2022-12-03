@if (\Acelle\Model\Setting::isYes('theme.beta'))
    @include('auth/t_login')
@else
    @include('auth/b_login')
@endif
