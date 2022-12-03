@if (Auth::user()->admin->getPermission("setting_system_urls") == 'yes')
    <div class="tab-pane active" id="top-system_urls">
        <div class="">
            <h3>{{ trans('messages.system_urls') }}</h3>
            @if (!$matched)
                <p>{!! trans('messages.admin.settings.system_urls.not_match', ['cached' => $cached, 'current' => $current]) !!}</p>
                <p><a href="{{ action("Admin\SettingController@updateUrls") }}" class="btn btn-secondary">{{ trans('messages.update_urls') }}</a></p>
            @endif
            <p>{{ trans('messages.admin.settings.system_urls.current_urls_wording') }}</p>
            <ul class="modern-listing mt-0 top-border-none">
            @foreach ($settings as $name => $setting)
                @if (array_key_exists('cat', $setting) && $setting['cat'] == 'url')
                    <li>
                        <div class="d-flex">
                            <span class="material-symbols-rounded fs-4 me-4">link</span>
                            <div>
                                <h5 class="mt-0 mb-0 text-semibold">
                                    {!! str_replace("LIST_UID", "<span class='text-info'>LIST_UID</span>",
                                    str_replace("SUBSCRIBER_UID", "<span class='text-info'>SUBSCRIBER_UID</span>",
                                    str_replace("SECURE_CODE", "<span class='text-info'>SECURE_CODE</span>",
                                    str_replace("STYLE", "<span class='text-info'>STYLE</span>",
                                    str_replace("MESSAGE_ID", "<span class='text-info'>MESSAGE_ID</span>",
                                    str_replace("URL", "<span class='text-info'>URL</span>",
                                    $setting['value'])))))) !!}
                                </h5>
                                <p class="fw-600 mt-1">
                                    {{ trans('messages.' . $name) }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach
            </ul>
        </div>
    </div>
@endif