<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top nav-underline">
            <li class="nav-item" rel0="PlanController/general">
                <a href="{{ action('Admin\PlanController@general', $plan->uid) }}" class="nav-link">
                    {{ trans('messages.plan.general') }}
                </a>
            </li>
            <li  class="nav-item dropdown"
                rel0="PlanController/quota"
                rel1="PlanController/security"
                rel2="PlanController/emailFooter"
            >
                <a class="nav-link dropdown-toggle" href="" class="level-1" data-bs-toggle="dropdown">
                    {{ trans('messages.plan.settings') }}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li rel0="PlanController/quota">
                        <a class="dropdown-item" href="{{ action('Admin\PlanController@quota', $plan->uid) }}">
                            {{ trans('messages.plan.quota') }}
                        </a>
                    </li>
                    <li rel0="PlanController/security">
                        <a class="dropdown-item" href="{{ action('Admin\PlanController@security', $plan->uid) }}">
                            {{ trans('messages.plan.security') }}
                        </a>
                    </li>
                    <li rel0="PlanController/emailFooter">
                        <a class="dropdown-item" href="{{ action('Admin\PlanController@emailFooter', $plan->uid) }}">
                            {{ trans('messages.plan.email_footer') }}
                        </a>
                    </li>
                    <li rel0="PlanController/tos">
                        <a class="dropdown-item" href="{{ action('Admin\PlanController@tos', $plan->uid) }}">
                            {{ trans('messages.plan.tos') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item" rel0="PlanController/sendingServer" rel1="PlanController/sendingServers" rel2="PlanController/sendingServerSubaccount"
                rel3="PlanController/sendingServerSystem"
                rel4="PlanController/sendingServerOwn"
            >                
                @if ($plan->useSystemSendingServer() && !$plan->hasPrimarySendingServer())
                    <a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}" class="nav-link level-1 xtooltip position-relative"
                         title="{{ trans('messages.plans.send_via.empty') }}"
                    >
                        {{ trans('messages.plan.sending_server') }}
                        <i class="material-symbols-rounded tabs-warning-icon text-danger">info</i>
                    </a>
                @else
                    <a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}" class="nav-link nav-link">
                        {{ trans('messages.plan.sending_server') }}
                    </a>
                @endif
                
            </li>
            <li class="nav-item" rel0="PlanController/emailVerification">
                <a href="{{ action('Admin\PlanController@emailVerification', $plan->uid) }}" class="nav-link nav-link">
                    {{ trans('messages.plan.email_verification') }}
                </a>
            </li>
        </ul>
    </div>
</div>
