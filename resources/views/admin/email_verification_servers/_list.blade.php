@if ($servers->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($servers as $key => $server)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $server->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <h5 class="m-0 text-bold">
                        <a class="kq_search" href="{{ action('Admin\EmailVerificationServerController@edit', ['email_verification_server' => $server->uid ]) }}">{{ $server->name }}</a>
                    </h5>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->admin->formatDateTime($server->created_at, 'date_full') }}</span>
                </td>
                <td>
                    <div class="single-stat-box pull-left ml-20">
                        <span class="no-margin stat-num kq_search">{{ $server->getTypeName() }}</span>
                        <br />
                        <span class="text-muted">{{ trans('messages.email_verification_server_type') }}</span>
                    </div>
                </td>
                <td>
                    <div class="single-stat-box pull-left ml-20">
                        <span class="text-muted"><strong>{{ trans('messages.email_verification_server.credits_usage', ['count' => number_with_delimiter($server->getCreditsUsed('verify')) ]) }}</strong></span>
                        <br />
                        <span class="text-muted2">{{ trans('messages.sending_server.speed', ['limit' => $server->getSpeedLimitString()]) }}</span>
                    </div>
                </td>
                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $server->status }}">{{ trans('messages.email_verification_server_status_' . $server->status) }}</span>
                    </span>
                </td>
                <td class="text-end text-nowrap pe-0">
                    @if (Auth::user()->admin->can('update', $server))
                        <a href="{{ action('Admin\EmailVerificationServerController@edit', ["email_verification_server" => $server->uid]) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary btn-icon"><span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}</a>
                    @endif
                    @if (Auth::user()->admin->can('delete', $server) || Auth::user()->admin->can('disable', $server) || Auth::user()->admin->can('enable', $server))
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->admin->can('enable', $server))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.enable_email_verification_servers_confirm') }}" href="{{ action('Admin\EmailVerificationServerController@enable', ["uids" => $server->uid]) }}">
                                            <span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->admin->can('disable', $server))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.disable_email_verification_servers_confirm') }}" href="{{ action('Admin\EmailVerificationServerController@disable', ["uids" => $server->uid]) }}">
                                            <span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->admin->can('delete', $server))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_email_verification_servers_confirm') }}" href="{{ action('Admin\EmailVerificationServerController@delete', ["uids" => $server->uid]) }}">
                                            <span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    @include('elements/_per_page_select', [
        'items' => $servers,
    ])

@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-symbols-rounded">
dns
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
dns
</span>
        <span class="line-1">
            {{ trans('messages.email_verification_server_empty_line_1') }}
        </span>
    </div>
@endif
