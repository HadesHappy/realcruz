<div class="row">
    <div class="col-md-12">
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-top nav-underline mb-4">
                <li class="nav-item" rel0="AccountController/profile">
                    <a href="{{ action("Admin\AccountController@profile") }}" class="nav-link">
                        <span class="material-symbols-rounded">
face
</span> {{ trans('messages.my_profile') }}
                    </a>
                </li>
                <li class="nav-item" rel0="AccountController/contact">
                    <a href="{{ action("Admin\AccountController@contact") }}" class="nav-link">
                        <span class="material-symbols-rounded">
maps_home_work
</span> {{ trans('messages.contact_information') }}
                    </a>
                </li>
                <li class="nav-item" rel0="AccountController/api">
                    <a href="{{ action("Admin\AccountController@api") }}" class="nav-link">
                        <span class="material-symbols-rounded">
vpn_key
</span> {{ trans('messages.api_token') }}
                    </a>
                </li>
                <li class="nav-item" rel0="NotificationController">
                    <a href="{{ action("Admin\NotificationController@index") }}" class="nav-link">
                        <span class="material-symbols-rounded">
restore
</span> {{ trans('messages.notifications') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
