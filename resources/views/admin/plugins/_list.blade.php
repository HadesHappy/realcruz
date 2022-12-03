@if ($plugins->count() > 0)
    <div class="mt-3 plugins-list-container mb-4 pb-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($plugins as $key => $plugin)
            <div class="p-4 shadow-sm bg-white rounded-3">
                <div class="d-flex">
                    <div>
                        <img class="plugin-icon me-4 pe-2" src="{{ $plugin->getIconUrl() ? $plugin->getIconUrl() : url('/images/plugin.svg') }}" />
                    </div>
                    <div class="plugin-title-column plugin-title-{{ $plugin->name }}" >
                        
                        <h5 class="no-margin text-bold kq_search">
                            @if (isset($settingUrls[$plugin->name]))
                                <a
                                    href="{{ $settingUrls[$plugin->name] }}"
                                    class=""
                                >
                                    {{ $plugin->title }} 
                                </a>
                            @else
                                {{ $plugin->title }} 
                            @endif                           
                        </h5>
                        <div class="mt-1 mb-3">
                            @if (array_key_exists($plugin->name, $blacklist))
                                <span style="cursor:pointer" class="text-muted2 list-status pull-left small">
                                    <span title="{{ $blacklist[$plugin->name] }}" class="xtooltip tooltipstered label label-flat bg-risky">
                                        error
                                    </span>
                                </span>
                            @else
                                <span class="text-muted2 list-status pull-left small">
                                    <span class="label label-flat bg-{{ $plugin->status }}">
                                        {{ trans('messages.email_verification_server_status_' . $plugin->status) }}
                                    </span>
                                </span>
                            @endif
                        </div>
                            
                        <span class="mt-1 d-block text-muted">
                            {{ $plugin->description }}
                        </span>
                        <span class="text-muted2 small mt-2 d-block">{{ trans('messages.plugin.name') }}: {{ $plugin->name }} | {{ trans('messages.plugin.version') }}: {{ $plugin->version }}</span>

                        <div class="text-left text-nowrap pe-0 ms-auto mt-4">
                            
        
                            @if (Auth::user()->admin->can('enable', $plugin))
                                <a link-confirm="{{ trans('messages.enable_plugins_confirm') }}"
                                    href="{{ action('Admin\PluginController@enable', ["uids" => $plugin->uid]) }}"
                                    class="btn btn-primary list-action-single"
                                >
                                    {{ trans('messages.enable') }}
                                </a>
                            @endif
        
                            @if (Auth::user()->admin->can('disable', $plugin))
                                <a link-confirm="{{ trans('messages.disable_plugins_confirm') }}"
                                    href="{{ action('Admin\PluginController@disable', ["uids" => $plugin->uid]) }}"
                                    class="btn btn-default list-action-single"
                                >
                                    {{ trans('messages.disable') }}
                                </a>
                            @endif

                            @if (isset($settingUrls[$plugin->name]))
                                <a
                                    href="{{ $settingUrls[$plugin->name] }}"
                                    class="btn btn-default"
                                >
                                    {{ trans('messages.setting') }}
                                </a>
                            @endif
        
                            <div class="btn-group">
                                <button role="button" class="btn btn-light icon-only dropdown-toggle" data-bs-toggle="dropdown">
                                    
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a
                                            class="dropdown-item list-action-single"
                                            link-confirm="{{ trans('messages.delete_plugins_confirm') }}"
                                            href="{{ action('Admin\PluginController@delete', ["uids" => $plugin->uid]) }}"
                                            role="button">
                                            <i class="material-symbols-rounded">delete</i> {{ trans('messages.uninstall') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    
                
            </div>
        @endforeach
        </div>
    @include('elements/_per_page_select', [
        'items' => $plugins
    ])
    
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list plugins-list">
        <span class="material-symbols-rounded">
            extension
            </span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list plugins-list">
        <span class="material-symbols-rounded">
            extension
            </span>
            <span class="line-1">
                <h5 class="mb-2">{{ trans('messages.plugin.no_plugin') }}</h5>
                <p>{!! trans('messages.plugin.no_plugin.wording') !!}</p>
            </span>
    </div>
@endif
