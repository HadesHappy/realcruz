<div class="row">
    <div class="col-md-12">
        <div class="tabbable pb-2">
            <ul class="nav nav-tabs nav-tabs-top nav-underline mb-4">
                <li rel0="AccountController/profile" class="nav-item">
                    <a href="{{ action("AccountController@profile") }}" class="nav-link">
                        <span class="material-symbols-rounded">
face
</span> {{ trans('messages.my_profile') }}
                    </a>
                </li>
                <li rel0="AccountController/contact" class="nav-item">
                    <a href="{{ action("AccountController@contact") }}" class="nav-link">
                        <span class="material-symbols-rounded">
maps_home_work
</span> {{ trans('messages.contact_information') }}
                    </a>
                </li>
                @if (config('app.saas'))
                    <li rel0="AccountController/billing" class="nav-item">
                        <a href="{{ action("AccountController@billing") }}" class="nav-link">
                            <span class="material-symbols-rounded">
    credit_card
    </span> {{ trans('messages.billing') }}
                        </a>
                    </li>
                    <li class="nav-item"
                        rel0="AccountController/subscription"
                        rel1="PaymentController"
                        rel2="AccountController/subscriptionNew"
                        rel3="SubscriptionController"
                        class="position-relative"
                    >
                        <a href="{{ action("SubscriptionController@index") }}" class="nav-link position-relative {{ isset($tab) && $tab == 'subscription' ? 'active' : '' }}">
                            <span class="material-symbols-rounded">
    assignment
    </span> {{ trans('messages.subscription') }}
                            @if (Auth::user()->customer->hasSubscriptionNotice())
                                <i class="material-symbols-rounded tabs-warning-icon text-danger">info</i>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="nav-item"
                    rel0="AccountController/logs">
                    <a href="{{ action("AccountController@logs") }}" class="nav-link">
                        <span class="material-symbols-rounded">
restore
</span> {{ trans('messages.logs') }}
                    </a>
                </li>
                @if (Auth::user()->customer->canUseApi())
                    <li class="nav-item" rel0="AccountController/api">
                        <a href="{{ action("AccountController@api") }}" class="nav-link">
                            <span class="material-symbols-rounded">
vpn_key
</span> {{ trans('messages.api_token') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
