@if ($automations->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($automations as $key => $automation)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $automation->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <a class="kq_search fw-600 d-block list-title" href="{{ action('Automation2Controller@edit', $automation->uid) }}">
                        {{ $automation->name }}
                    </a>
                    <div class="" data-popup="tooltip">
                        {{ $automation->getBriefIntro() }}
                    </div>
                </td>
                <td>
                    <h5 class="no-margin stat-num">
                        {{ $automation->mailList->readCache('SubscriberCount', '#') }}
                    </h5>
                    <span class="text-muted2">{{ trans('messages.automation.overview.contacts') }}</span>
                </td>
                <td>
                    <h5 class="no-margin text-primary stat-num">
                        {{ $automation->countEmails() }}
                    </h5>
                    <span class="text-muted2">{{ trans('messages.emails') }}</span>
                </td>
                <td>
                    <h5 class="no-margin text-primary stat-num">
                        {{ $automation->readCache('SummaryStats') ? number_to_percentage($automation->readCache('SummaryStats')['complete']) : '#' }}
                    </h5>
                    <span class="text-muted2">{{ trans('messages.complete') }}</span>
                </td>
                <td>
                    <span class="no-margin text-bold">
                        {{ $automation->updated_at->diffForHumans(['options' => 2]) }}
                    </span>
                    <br />
                    <span class="text-muted">{{ trans('messages.automation.action.last_updated') }}</span>
                </td>
                <td class="text-center">
                    <span class="text-muted2 list-status">
                        @if (empty($automation->last_error))
                            <span class="label label-flat bg-{{ $automation->status }}">{{ trans('messages.automation.status.' . $automation->status) }}</span>
                        @else
                            <!--
                                Automations with .last_error are still of "active" status
                                As a result, thy will still be executed at the next cronjob check
                                and the error might be solved then
                            -->
                            <span class="label xtooltip label-flat bg-error" title="{{ $automation->last_error }}">Error</span>
                            <pre style="display:none">{{ $automation->last_error }}</pre>
                        @endif
                    </span>
                </td>
                <td class="text-end text-nowrap pe-0">
                    @if (\Gate::allows('update', $automation))
                        <a data-popup="tooltip" href="{{ action('Automation2Controller@edit', $automation->uid) }}" role="button" class="btn btn-secondary btn-icon">
                            <i class="material-symbols-rounded">multiline_chart</i> {{ trans('messages.automation.design') }}
                        </a>
                    @endif
                    @if (\Gate::allows('delete', $automation) || Auth::user()->can('disable', $automation) || Auth::user()->can('enable', $automation))
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('enable', $automation)
                                    <li>
                                        <a
                                            class="dropdown-item list-action-single"
                                            link-method="PATCH"
                                            link-confirm="{{ trans('messages.enable_automations_confirm') }}"
                                            href="{{ action('Automation2Controller@enable', ["uids" => $automation->uid]) }}"
                                        >
                                            <span class="material-symbols-rounded me-2">
play_arrow
</span> {{ trans('messages.enable') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('disable', $automation)
                                    <li>
                                        <a
                                            class="dropdown-item list-action-single"
                                            link-method="PATCH" link-confirm="{{ trans('messages.disable_automations_confirm') }}"
                                            href="{{ action('Automation2Controller@disable', ["uids" => $automation->uid]) }}"
                                        >
                                            <span class="material-symbols-rounded me-2">
hide_source
</span> {{ trans('messages.disable') }}
                                        </a>
                                    </li>
                                @endcan
                                @if (\Gate::allows('delete', $automation))
                                    <li>
                                        <a  
                                            class="dropdown-item list-action-single"
                                            link-method='delete'
                                            link-confirm="{{ trans('messages.delete_automations_confirm') }}"
                                            href="{{ action('Automation2Controller@delete', ["uids" => $automation->uid]) }}"
                                        >
                                            <span class="material-symbols-rounded me-2">
delete_outline
</span> {{ trans("messages.delete") }}
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
    @include('elements/_per_page_select', ["items" => $automations])
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
            schedule
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
schedule
</span>
        <span class="line-1">
            {{ trans('messages.automation_empty_line_1') }}
        </span>
    </div>
@endif
