<li class="nav-item dropdown">
    <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center ps-3 pe-1 py-3 lvl-1 dropdown-toggle"
        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="navbar-icon">
            <img src="{{ Auth::user()->getProfileImageUrl() }}" style="border-radius:100%;" class="menu-user-avatar" alt="">
        </i>
        <span>{{ Auth::user()->displayName() }}</span>
        @if (Auth::user()->customer->hasSubscriptionNotice())
            <i class="material-symbols-rounded customer-warning-icon text-danger" style="right: 3px!important;
            color: rgb(236, 124, 124)!important;
            top: 2px!important;
            position: absolute;
            text-indent: 0;transform:scale(1.2)">info</i>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-bottom top-user-menus" aria-labelledby="content-menu">
        <li class="backdrop  backdrop-frontend p-4 d-flex align-items-center justify-content-center">
            <img src="{{ url('images/paper-airplane.svg') }}" width="100px" />
        </li>
        <li class="p-3" style="width: 300px">
            <div class="d-flex mb-2">
                <div class="big-avatar me-3">
                    <a href="{{ action('AccountController@profile') }}">
                        <img width="80px" class="" src="{{ Auth::user()->getProfileImageUrl() }}"
                            style="border-radius:100%"
                            class="menu-user-avatar" alt="">
                    </a>
                </div>
                <div>
                    <a href="{{ action('AccountController@profile') }}" class="fs-6 fw-600 d-flex align-items-center">
                        <span class="me-3">{{ Auth::user()->displayName() }}</span>
                    </a>
                    <p class="mb-1 small text-muted">{{ Auth::user()->email }}</p>
                    @if (Auth::user()->customer->status) 
                        <span class="label label-flat bg-{{ Auth::user()->customer->status }}">{{ trans('messages.' . Auth::user()->customer->status) }}</span>
                    @endif
                </div>
            </div>
        </li>
        @if (config('app.saas'))
            @can("admin_access", Auth::user())
                <li class="border-bottom"><a href="{{ action("Admin\HomeController@index") }}" class="dropdown-item d-flex align-items-center">
                <i class="navbar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 93.8 89" style="enable-background:new 0 0 93.8 89;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M59.2,89H18.4C8.3,89,0,79.6,0,68.1V20.8C0,9.3,8.3,0,18.4,0h40.8c9.4,0,17.3,8,18.3,18.7l-7,0.6 C69.9,12.3,65,7,59.2,7H18.4C12.1,7,7,13.2,7,20.8v47.3C7,75.8,12.1,82,18.4,82h40.8c5.8,0,10.7-5.3,11.3-12.4l7,0.6 C76.5,80.9,68.7,89,59.2,89z"/><rect x="40.6" y="41" class="st0" width="24.1" height="7"/><polygon class="st0" points="58.8,64.7 93.8,44.5 58.8,24.2 "/></g></g></svg>
                </i>{{ trans('messages.admin_view') }}</a></li>
                <li class="divider"></li>
            @endif
            @if (request()->user()->customer->activeSubscription())
                <li class="nav-item">
                    <a href="#" class="dropdown-item top-quota-button d-flex align-items-center" data-url="{{ action("AccountController@quotaLog") }}">
                    <i class="navbar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 102.5 100.8" style="enable-background:new 0 0 102.5 100.8;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M18.3,92.5h-2.5C7.1,92.5,0,85.4,0,76.7V15.8C0,7.1,7.1,0,15.8,0h60.9c8.7,0,15.8,7.1,15.8,15.8v5.5 c0,1.9-1.6,3.5-3.5,3.5s-3.5-1.6-3.5-3.5v-5.5c0-4.9-3.9-8.8-8.8-8.8H15.8C10.9,7,7,10.9,7,15.8v60.9c0,4.9,3.9,8.8,8.8,8.8h2.5 c1.9,0,3.5,1.6,3.5,3.5S20.2,92.5,18.3,92.5z"/><path class="st0" d="M65.3,100.8c-20.5,0-37.2-16.7-37.2-37.2c0-20.5,16.7-37.2,37.2-37.2c20.5,0,37.2,16.7,37.2,37.2 C102.5,84.1,85.8,100.8,65.3,100.8z M65.3,33.4c-16.7,0-30.2,13.5-30.2,30.2s13.5,30.2,30.2,30.2s30.2-13.5,30.2-30.2 S82,33.4,65.3,33.4z"/><path class="st0" d="M83,81.8c-0.7,0-1.4-0.2-2-0.7L63.4,68.4c-0.9-0.6-1.4-1.6-1.5-2.7l-0.6-16.4c-0.1-1.9,1.4-3.6,3.4-3.6 c1.9-0.1,3.6,1.4,3.6,3.4l0.5,14.7L85,75.5c1.6,1.1,1.9,3.3,0.8,4.9C85.2,81.3,84.1,81.8,83,81.8z"/><path class="st0" d="M40,23.6H19.5c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H40c1.9,0,3.5,1.6,3.5,3.5S41.9,23.6,40,23.6z"/><path class="st0" d="M25.8,36.7h-6.3c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h6.3c1.9,0,3.5,1.6,3.5,3.5S27.7,36.7,25.8,36.7z"/></g></g></svg>
                </i>
                        <span class="">{{ trans('messages.used_quota') }}</span>
                    </a>
                </li>
            @endif
            <li class="nav-item" rel0="SubscriptionController/index">
                <a href="{{ action('SubscriptionController@index') }}" class="dropdown-item d-flex align-items-center">
                <i class="navbar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 92 94.2" style="enable-background:new 0 0 92 94.2;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M46.1,94.2c-12.3,0-24.6-4.8-33.8-14.3c-1.3-1.4-1.3-3.6,0.1-4.9c1.4-1.3,3.6-1.3,4.9,0.1 C32.7,90.9,58,91.3,73.9,76c5.6-5.4,9.5-12.3,11.2-20c0.4-1.9,2.3-3.1,4.2-2.6c1.9,0.4,3.1,2.3,2.6,4.2c-2,9-6.6,17.1-13.2,23.5 C69.6,89.8,57.8,94.2,46.1,94.2z"/><polygon class="st0" points="4.1,89.6 3.5,56.8 32.2,72.6 "/><path class="st0" d="M3.5,40.9c-0.3,0-0.5,0-0.8-0.1c-1.9-0.4-3.1-2.3-2.6-4.2C5,15.1,23.9,0,46,0c0.1,0,0.1,0,0.2,0 C58.8,0,71,5.2,79.7,14.3c1.3,1.4,1.3,3.6-0.1,4.9c-1.4,1.3-3.6,1.3-4.9-0.1C67.3,11.4,56.9,7,46.2,7c-0.1,0-0.1,0-0.2,0 C27.2,7,11.1,19.8,6.9,38.2C6.5,39.8,5.1,40.9,3.5,40.9z"/><polygon class="st0" points="59.8,21.6 88.5,37.4 87.9,4.6 "/><path class="st0" d="M43.3,69.9V64c-3.4,0-6.8-0.9-9.8-2.6l1.7-5.6c2.8,1.6,6,2.5,9.3,2.6c3.8,0,6.4-1.9,6.4-4.7s-2.1-4.5-6.6-6.1 C38,45.3,34,42.4,34,36.9s3.6-9.1,9.6-10.2v-5.9h4.9v5.6c2.9,0,5.7,0.7,8.3,2l-1.6,5.5c-2.5-1.4-5.3-2.1-8.2-2.1 c-4.2,0-5.7,2.2-5.7,4.2s2.2,3.9,7.3,5.9c6.7,2.5,9.7,5.8,9.7,11.1s-3.5,9.6-10.1,10.7v6.2H43.3z"/></g></g></svg>
                </i>{{ trans('messages.subscriptions') }}
                    <span class="position-relative" style="width:100%">
                        @if (Auth::user()->customer->hasSubscriptionNotice())
                            <i class="material-symbols-rounded subscription-warning-icon text-danger" style="font-size: inherit;
                                position: absolute;
                                right: 0;
                                top: 0;margin-top: -10px">info</i>
                        @endif
                    </span>
                </a>
            </li>
            <li class="nav-item" rel0="AccountController/billing"><a href="{{ action("AccountController@billing") }}" class="dropdown-item d-flex align-items-center">
                <i class="navbar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 838.9 665.3" style="enable-background:new 0 0 838.9 665.3;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M735.8,665.3H103.1C46.3,665.3,0,619.1,0,562.2V103.1C0,46.3,46.3,0,103.1,0h632.7 c56.8,0,103.1,46.3,103.1,103.1v459.1C838.9,619.1,792.6,665.3,735.8,665.3z M103.1,55C76.6,55,55,76.6,55,103.1v459.1 c0,26.5,21.6,48.1,48.1,48.1h632.7c26.5,0,48.1-21.6,48.1-48.1V103.1c0-26.5-21.6-48.1-48.1-48.1H103.1z"/><path class="st0" d="M330.1,364.4H161.9c-15.2,0-27.5-12.3-27.5-27.5V168.7c0-15.2,12.3-27.5,27.5-27.5h168.2 c15.2,0,27.5,12.3,27.5,27.5v168.2C357.6,352.1,345.3,364.4,330.1,364.4z M189.4,309.4h113.2V196.2H189.4V309.4z"/><path class="st0" d="M626.1,215.8H449.6c-15.2,0-27.5-12.3-27.5-27.5s12.3-27.5,27.5-27.5h176.5c15.2,0,27.5,12.3,27.5,27.5 S641.3,215.8,626.1,215.8z"/><path class="st0" d="M626.1,360.4H449.6c-15.2,0-27.5-12.3-27.5-27.5s12.3-27.5,27.5-27.5h176.5c15.2,0,27.5,12.3,27.5,27.5 S641.3,360.4,626.1,360.4z"/><path class="st0" d="M636.7,521.4H161.9c-15.2,0-27.5-12.3-27.5-27.5s12.3-27.5,27.5-27.5h474.8c15.2,0,27.5,12.3,27.5,27.5 S651.9,521.4,636.7,521.4z"/></g></g></svg>
                </i>{{ trans('messages.billing') }}
            
            </a></li>
        @endif
        <li class="nav-item" rel0="AccountController/profile"><a href="{{ action("AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
            <i class="navbar-icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 88.3 88.3" style="enable-background:new 0 0 88.3 88.3;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><g><path id="Layer_1-3_1_" class="st0" d="M44.2,1C20.4,0.9,1,20.1,0.9,43.9c0,12.4,5.2,24.1,14.5,32.4l0,0l0.6,0.6l0.4,0.3 c1.8,1.5,3.7,2.8,5.7,4.1l0.5,0.2l3,1.7h0.1c2.3,1,4.6,1.9,7,2.6h0.1l3.6,0.8h0.3l3.5,0.5h8l3.5-0.5H52l3.5-0.8h0.1 c2.4-0.7,4.8-1.6,7.1-2.6l0,0c1.1-0.5,2.1-1.1,3.1-1.7l0.5-0.2l2.6-1.8l0.7-0.5l2.4-1.8l0.3-0.3l0.7-0.6l0,0 c17.7-16,19.2-43.3,3.2-61C68,6.2,56.4,1,44.2,1z M44.2,44.2c-8,0-14.4-6.4-14.4-14.4c0-8,6.4-14.4,14.4-14.4s14.4,6.4,14.4,14.4 S52.2,44.2,44.2,44.2L44.2,44.2z M49.6,47.8c10.9,0,19.8,8.9,19.8,19.8v7.1l-0.3,0.2l-2.2,1.6l-0.4,0.4L64,78.4l-0.5,0.3 c-3.8,2.1-8,3.6-12.3,4.4h-0.3l-3.1,0.4h-7.2L37.2,83c-3.2-0.6-6.4-1.5-9.4-2.9h-0.3c-1.1-0.5-2.1-1.1-3.1-1.7l-2.5-1.5l-0.5-0.4 l-2.2-1.6c-0.1-0.1-0.1-0.2-0.2-0.2v-7.1c0-10.9,8.9-19.8,19.8-19.8h0H49.6z M73,71.3v-3.7c0-11.1-7.8-20.7-18.7-22.9 c4.9-3.4,7.8-8.9,7.9-14.9c0-9.9-8.1-18-18-18s-18,8.1-18,18c0,6,3,11.5,7.9,14.9c-10.9,2.2-18.7,11.8-18.7,22.9v3.7 C0.5,55.4,1.3,30.4,17.2,15.5S58.1,1.3,73,17.2C87.3,32.4,87.3,56.1,73,71.3z"/><path class="st0" d="M48.3,88.1l-8.2,0l-3.9-0.5l-3.8-0.8c-2.4-0.7-4.7-1.6-6.9-2.6h-0.1l-3.2-1.8l-0.6-0.3c-2-1.3-4-2.7-5.8-4.2 l-0.5-0.4L14.7,77C5.3,68.6-0.1,56.6-0.1,43.9C0,19.7,19.8,0,44,0c0.1,0,0.1,0,0.2,0c12.5,0,24.4,5.4,32.7,14.6 C93.2,32.7,91.8,60.7,73.7,77l-1.1,1l-3.1,2.3l-2.8,1.9l-0.4,0.2c-1,0.6-2.1,1.2-3.1,1.7c-2.4,1.1-4.8,1.9-7.3,2.7l-0.3,0 L52,87.6h-0.2L48.3,88.1z M40.3,86.1h7.9l3.8-0.5l3.6-0.8c2.3-0.7,4.6-1.5,6.8-2.5c1-0.5,2-1,3-1.6l0.2-0.1l0.4-0.2l3.2-2.2 l2.6-2l0.8-0.7C89.6,60,91,33.2,75.4,16C67.5,7.1,56.1,2,44.2,2h0c0,0-0.1,0-0.2,0C20.9,2,2,20.8,1.9,44 c0,12.1,5.1,23.6,14.1,31.6l0.6,0.6l0.3,0.3c1.8,1.5,3.6,2.8,5.6,4l0.5,0.2l3,1.7c2.2,1,4.4,1.8,6.8,2.5l0.2,0l3.8,0.8L40.3,86.1 z M47.9,84.5l-7.4,0L37.1,84c-3.3-0.6-6.5-1.5-9.5-2.9h-0.3L27.1,81c-1.1-0.5-2.2-1.1-3.2-1.8l-2.6-1.6l-0.5-0.4l-2.2-1.6H18 v-8.1c0-11.5,9.3-20.8,20.8-20.8h10.8c11.5,0,20.8,9.3,20.8,20.8v7.6l-0.7,0.5l-2.1,1.5L67,77.8l-3,1.8c-4,2.2-8.2,3.7-12.6,4.5 l-0.4,0L47.9,84.5z M40.7,82.5h7.1l3.4-0.4c4.2-0.8,8.2-2.2,11.9-4.3l2.9-1.7l0.4-0.4l2.1-1.5v-6.6c0-10.4-8.4-18.8-18.8-18.8 H38.8C28.4,48.8,20,57.2,20,67.6v6.6l2.5,1.8l2.4,1.5c0.9,0.6,1.9,1.1,2.8,1.6H28l0.2,0.1c2.9,1.3,6,2.3,9.2,2.8L40.7,82.5z M16.4,73.8L14.7,72c-7.4-7.9-11.3-18.2-11-29c0.3-10.8,4.9-20.8,12.8-28.2c16.3-15.3,42-14.5,57.2,1.8 c14.6,15.5,14.6,39.9,0,55.4L72,73.8v-6.2c0-10.6-7.5-19.8-17.9-21.9l-2.3-0.5l1.9-1.3c4.6-3.2,7.4-8.5,7.5-14.1 c0-9.4-7.6-17-17-17c-9.4,0-17,7.6-17,17c0,5.6,2.8,10.9,7.5,14.1l2,1.3l-2.3,0.5C23.9,47.8,16.4,57,16.4,67.6V73.8z M44.2,5.8 c-9.5,0-18.9,3.4-26.3,10.4C10.4,23.2,6,32.8,5.7,43c-0.3,9.4,2.8,18.4,8.7,25.6v-1c0-10.8,7.2-20.4,17.4-23.4 c-4.2-3.6-6.6-8.8-6.6-14.4c0-5.1,2-9.8,5.6-13.4s8.4-5.6,13.4-5.6c10.5,0,19,8.5,19,19c0,5.6-2.5,10.8-6.7,14.4 C66.8,47.3,74,56.8,74,67.6v1c12.1-14.8,11.5-36.6-1.7-50.7C64.7,9.9,54.5,5.8,44.2,5.8z M44.2,45.2c-8.5,0-15.4-6.9-15.4-15.4 s6.9-15.4,15.4-15.4c8.5,0,15.4,6.9,15.4,15.4S52.7,45.2,44.2,45.2z M44.2,16.4c-7.4,0-13.4,6-13.4,13.4c0,7.4,6,13.4,13.4,13.4 c7.4,0,13.4-6,13.4-13.4C57.6,22.4,51.6,16.4,44.2,16.4z"/></g></g></g></svg>
            </i>{{ trans('messages.account') }}
        
        </a></li>
        @if (!config('app.saas'))
            <li class="nav-item border-top" rel0="AccountController/logs"><a href="{{ action("AccountController@logs") }}" class="dropdown-item d-flex align-items-center">
                <i class="navbar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 109.3 94.4"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="Layer_2-2" data-name="Layer 2"><g id="Layer_1-2-2" data-name="Layer 1-2"><g id="Layer_2-2-2" data-name="Layer 2-2"><g id="Layer_1-2-2-2" data-name="Layer 1-2-2"><path d="M62.2,94.4a45.3,45.3,0,0,1-5.2-.3,48.1,48.1,0,0,1-18.1-5.8,3.5,3.5,0,0,1-1.3-4.8h0A3.2,3.2,0,0,1,42,82.1h.3A40.2,40.2,0,1,0,22.2,42.8a3.6,3.6,0,0,1-3.9,3.1A3.5,3.5,0,0,1,15.2,42h0a47.2,47.2,0,1,1,47,52.4Z" style="fill:#333"/><polygon points="38.6 33.3 24.8 69.9 0 39.7 38.6 33.3" style="fill:#333"/><path d="M84.2,63.8a3.3,3.3,0,0,1-1.7-.4L60.4,51.2a3.5,3.5,0,0,1-1.8-3.1V29a3.5,3.5,0,1,1,7,0V46.1L85.9,57.3A3.5,3.5,0,0,1,87.3,62,3.5,3.5,0,0,1,84.2,63.8Z" style="fill:#333"/></g></g></g></g></g></g></svg>
                </i>{{ trans('messages.activities') }}
            
            </a></li>
        @endif
        <li><a href="{{ url("/logout") }}" class="dropdown-item d-flex align-items-center">
            <i class="navbar-icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 83.9 85.1" style="enable-background:new 0 0 83.9 85.1;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M41.8,85.1c-7.9,0-15.7-2.3-22.5-6.6C-0.1,66-5.8,40,6.6,20.6c3.6-5.6,8.6-10.4,14.3-13.7 c1.7-1,3.8-0.4,4.8,1.3c1,1.7,0.4,3.8-1.3,4.8c-4.8,2.8-8.9,6.7-11.9,11.4C2.1,40.6,6.9,62.2,23.1,72.6 C39.3,82.9,60.9,78.2,71.3,62c5-7.9,6.7-17.2,4.7-26.3s-7.4-16.9-15.3-21.9c-1.6-1-2.1-3.2-1.1-4.8c1-1.6,3.2-2.1,4.8-1.1 c9.4,6,15.9,15.4,18.3,26.3s0.4,22.1-5.6,31.6c-6,9.4-15.4,15.9-26.3,18.3C47.9,84.7,44.8,85.1,41.8,85.1z"/><path class="st0" d="M41.9,41.2c-1.9,0-3.5-1.6-3.5-3.5V3.5C38.4,1.6,40,0,41.9,0s3.5,1.6,3.5,3.5v34.2 C45.4,39.6,43.8,41.2,41.9,41.2z"/></g></g></svg>
            </i>{{ trans('messages.logout') }}
        </a></li>
    </ul>
</li>