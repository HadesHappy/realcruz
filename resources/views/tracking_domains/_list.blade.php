@if ($trackingDomains->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($trackingDomains as $key => $trackingDomain)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $trackingDomain->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <h5 class="m-0 text-bold">
                        <a class="kq_search d-block" href="{{ action('TrackingDomainController@show', $trackingDomain->uid) }}">{{ $trackingDomain->name }}</a>
                    </h5>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->customer->formatDateTime($trackingDomain->created_at, 'date_full') }}</span>
                </td>
                <td>
                    <div class="single-stat-box pull-left">
                        <span class="no-margin stat-num">{{ strtoupper($trackingDomain->scheme) }}</span>
                        <br>
                        <span class="text-muted text-nowrap">{{ trans('messages.tracking_domain.scheme') }}</span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $trackingDomain->status }}">
                            {{ trans('messages.tracking_domain.status.' . $trackingDomain->status) }}</span>
                    </span>
                </td>
                <td class="text-end">
                    
                    @if (Auth::user()->customer->can('read', $trackingDomain))
                        <a href="{{ action('TrackingDomainController@show', $trackingDomain->uid) }}" data-popup="tooltip"
                            title="{{ trans('messages.tracking_domain.view') }}" role="button" class="btn btn-secondary">
                            <span class="material-symbols-rounded">
                                zoom_in
                                </span> {{ trans('messages.tracking_domain.view') }}
                        </a>
                    @endif
                    @if (Auth::user()->customer->can('delete', $trackingDomain))
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a
                                        class="dropdown-item list-action-single"
                                        link-confirm="{{ trans('messages.delete_tracking_domains_confirm') }}" href="{{ action('TrackingDomainController@delete', ["uids" => $trackingDomain->uid]) }}">
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
    @include('elements/_per_page_select', [
        'items' => $trackingDomains,
    ])
    
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
            {{ trans('messages.tracking_domain_empty_line_1') }}
        </span>
    </div>
@endif
