<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top nav-underline mb-1">
            <li rel0="MailListController/overview" class="nav-item">
                <a href="{{ action('MailListController@overview', $list->uid) }}" class="nav-link">
                <span class="material-symbols-rounded">
auto_graph
</span> {{ trans('messages.overview') }}
                </a>
            </li>
            <li rel0="MailListController/edit" class="nav-item">
                <a class="nav-link level-1" href="{{ action('MailListController@edit', $list->uid) }}">
                <span class="material-symbols-rounded">
settings
</span> {{ trans('messages.list.title.setting') }}
                </a>
            </li>
            <li rel0="SubscriberController" class="nav-item">
                <a href="{{ action("AccountController@contact") }}" class="nav-link dropdown-toggle level-1" data-bs-toggle="dropdown">
                <span class="material-symbols-rounded">
people
</span> {{ trans('messages.subscribers') }}
                <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="nav-item" rel0="SubscriberController/index">
                        <a class="dropdown-item" href="{{ action('SubscriberController@index', $list->uid) }}">
                        <span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.view_all') }}
                        </a>
                    </li>
                    <li class="nav-item" rel0="SubscriberController/create">
                        <a class="dropdown-item" href="{{ action('SubscriberController@create', $list->uid) }}">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.add') }}
                        </a>
                    </li>
                    <li class="divider"></li>
                    @if (\Auth::user()->can('import', $list))
                    <li class="nav-item" rel0="SubscriberController/import">
                        <a class="dropdown-item" class="dropdown-item" href="{{ action('SubscriberController@import', $list->uid) }}">
                        <span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.import') }}
                        </a>
                    </li>
                    @endif
                    @if (\Auth::user()->can('export', $list))
                    <li class="nav-item" rel0="SubscriberController/export">
                        <a class="dropdown-item" href="{{ action('SubscriberController@export', $list->uid) }}">
                        <span class="material-symbols-rounded">
file_download
</span> {{ trans('messages.export') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li rel0="SegmentController" class="nav-item">
                <a href="{{ action("AccountController@contact") }}" class="nav-link level-1 dropdown-toggle" data-bs-toggle="dropdown">
                <span class="material-symbols-rounded">
splitscreen
</span> {{ trans('messages.segments') }}
                <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="nav-item" rel0="SegmentController/index">
                    <a class="dropdown-item" href="{{ action('SegmentController@index', $list->uid) }}">
                    <span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.view_all') }}
                    </a>
                    </li>
                    <li class="nav-item" rel0="SegmentController/create">
                    <a class="dropdown-item" href="{{ action('SegmentController@create', $list->uid) }}">
                    <span class="material-symbols-rounded">
add
</span> {{ trans('messages.add') }}
                    </a>
                    </li>
                </ul>
            </li>
            <li rel0="PageController" rel1="MailListController/embeddedForm" class="nav-item">
                <a href="#menu" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <span class="material-symbols-rounded">
web
</span> {{ trans('messages.custom_forms_and_emails') }}
                <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end has-head">
                <li>
                    <a class="dropdown-item" href="{{ action('MailListController@embeddedForm', $list->uid) }}">
                    <span class="material-symbols-rounded">
dashboard
</span> {{ trans('messages.Embedded_form') }}
                    </a>
                </li>
                <li class="head">
                    <span class="material-symbols-rounded me-1">
                        add_task
                        </span> {{ trans('messages.subscribe') }}
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'sign_up_form']) }}">
                    {{ trans('messages.sign_up_form') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'sign_up_thankyou_page']) }}">
                    {{ trans('messages.sign_up_thankyou_page') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'sign_up_confirmation_email']) }}">
                    {{ trans('messages.sign_up_confirmation_email') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'sign_up_confirmation_thankyou']) }}">
                    {{ trans('messages.sign_up_confirmation_thankyou') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'sign_up_welcome_email']) }}">
                    {{ trans('messages.sign_up_welcome_email') }}
                    </a>
                </li>
                <li class="head">
                    <span class="material-symbols-rounded me-1">
                        logout
                        </span> {{ trans('messages.unsubscribe') }}
                    </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'unsubscribe_form']) }}">
                    {{ trans('messages.unsubscribe_form') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'unsubscribe_success_page']) }}">
                    {{ trans('messages.unsubscribe_success_page') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'unsubscribe_goodbye_email']) }}">
                    {{ trans('messages.unsubscribe_goodbye_email') }}
                    </a>
                </li>
                    <li class="head">
                        <span class="material-symbols-rounded me-1">
                            person_outline
                            </span> {{ trans('messages.update_profile') }}
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'profile_update_email_sent']) }}">
                    {{ trans('messages.profile_update_email_sent') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'profile_update_email']) }}">
                    {{ trans('messages.profile_update_email') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'profile_update_form']) }}">
                    {{ trans('messages.profile_update_form') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => 'profile_update_success_page']) }}">
                    {{ trans('messages.profile_update_success_page') }}
                    </a>
                </li>
                </ul>
            </li>
            <li rel0="FieldController/index" class="nav-item">
                <a class="nav-link" href="{{ action('FieldController@index', $list->uid) }}">
                <span class="material-symbols-rounded">
fact_check
</span> {{ trans('messages.manage_list_fields') }}
                </a>
            </li>
            <li rel0="MailListController/verification" class="nav-item">
                <a class="nav-link" href="{{ action('MailListController@verification', $list->uid) }}">
                <span class="material-symbols-rounded">
mark_email_read
</span> {{ trans('messages.email_verification') }}
                </a>
            </li>
        </ul>
    </div>
</div>
