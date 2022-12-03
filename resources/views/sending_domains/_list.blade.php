@if ($items->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($items as $key => $item)
            @php
                if (is_null($server)) {
                    $allowedByServer = true;
                } else {
                    $allowedByServer = $item->isAllowedBy($server);
                }

            @endphp
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $item->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <h5 class="mb-1 text-bold">
                        @if ($allowedByServer)
                            <a class="kq_search" href="{{ action('SendingDomainController@show', $item->uid) }}">{{ $item->name }}</a>
                        @else
                            <span>{{ $item->name }}</span>
                        @endif
                    </h5>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->customer->formatDateTime($item->created_at, 'date_full') }}</span>
                </td>
                
                <td class="text-center">
                    <div class="single-stat-box pull-left">
                        @if ($item->isIdentityVerified())
                            <span class="material-symbols-rounded d-block text-success domain-check-icon">
                                task_alt
                            </span>
                        @else
                        <span class="material-symbols-rounded d-block text-danger domain-check-icon">
                            not_interested
                            </span>
                        @endif
                        <span class="text-muted">{{ trans("messages.sending_domain.domain_verification") }}</span>
                    </div>
                </td>
                    
                <td class="text-center">
                    <div class="single-stat-box pull-left">
                        @if ($item->isDkimVerified())
                            <span class="material-symbols-rounded d-block text-success domain-check-icon">
                                task_alt
                                </span>
                        @else
                        <span class="material-symbols-rounded d-block text-danger domain-check-icon">
                            not_interested
                            </span>
                        @endif
                        <span class="text-muted">{{ trans("messages.sending_domain.dkim_verification") }}</span>
                    </div>
                </td>
                
                <td class="text-center">
                    @if ($item->isSpfNeeded())
                        <div class="single-stat-box pull-left">
                            @if ($item->isSpfVerified())
                                <span class="material-symbols-rounded d-block text-success domain-check-icon">
                                    task_alt
                                    </span>
                            @else
                            <span class="material-symbols-rounded d-block text-danger domain-check-icon">
                                not_interested
                                </span>
                            @endif
                            <span class="text-muted">{{ trans("messages.sending_domain.spf_verification") }}</span>
                        </div>
                    @endif
                </td>
                <td class="text-center pt-4">
                    @if ($allowedByServer)
                        <span class="text-muted2 list-status pull-left">
                            <span class="label label-flat bg-{{ $item->status }}">{{ trans('messages.sending_domain_status_' . $item->status) }}</span>
                        </span>
                    @else
                        <span class="xtooltip text-muted2 list-status pull-left" style="cursor:pointer" title="{{ trans('messages.sending_domain.status.outdated') }}">
                            <span class="label label-flat bg-error">{{ trans('messages.sending_domain_status_inactive') }}</span>
                        </span>
                    @endif
                </td>
                <td class="text-end">
                    
                    @if (Auth::user()->customer->can('read', $item))
                        <a href="{{ action('SendingDomainController@show', $item->uid) }}" data-popup="tooltip"
                            title="{{ trans('messages.sending_domain.view') }}" role="button" class="btn btn-secondary btn-icon">
                                {{ trans('messages.sending_domain.view') }}
                        </a>
                    @endif

                    @if (Auth::user()->customer->can('delete', $item))
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_sending_domains_confirm') }}" href="{{ action('SendingDomainController@delete', ["uids" => $item->uid]) }}">
                                        <span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select')
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
public
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
public
</span>
        <span class="line-1">
            {{ trans('messages.sending_domain_empty_line_1') }}
        </span>
    </div>
@endif
