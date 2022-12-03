@if ($plans->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($plans as $key => $plan)
            <tr>
                <td width="1%">
                    <div class="text-nowrap d-flex align-items-canter">
                        @if (!$plan->visible)
                            <a
                                title="{{ trans('messages.plan.show') }}"
                                link-method="POST"
                                href="{{ action('Admin\PlanController@visibleOn', $plan->uid) }}"
                                class="list-action-single plan-off {{ (\Auth::user()->can('visibleOn', $plan) ? 'xtooltip' : 'cant_show') }}"
                            >
                                <i class="material-symbols-rounded plan-off-icon fs-3 me-3">toggle_off</i>
                            </a>
                        @else
                            <a
                                title="{{ trans('messages.plan.hide') }}"
                                link-confirm="{{ trans('messages.plans.hide.confirm') }}"
                                link-method="POST"
                                href="{{ action('Admin\PlanController@visibleOff', $plan->uid) }}"
                                class="list-action-single plan-on {{ (\Auth::user()->can('visibleOff', $plan) ? 'xtooltip' : 'disabled') }}"
                            >
                                <i class="material-symbols-rounded plan-on-icon fs-3 me-3">toggle_on</i>
                            </a>
                        @endif                        
                    </div>
                </td>
                <td>
                    <h5 class="m-0 text-bold">
                        <span class="kq_search d-block" href="{{ action('Admin\PlanController@general', $plan->uid) }}">
                            {{ $plan->name }}
                        </span>
                    </h5>
                    <p class="mb-0">{{ $plan->description }}</p>
                    @if (!$plan->useSystemSendingServer())
                        <span class="text-muted small" class="">{{ trans('messages.plan.sending_server.' . $plan->getOption('sending_server_option')) }}  &bull; {{ trans('messages.plans.subscriber_count', ['count' => $plan->customersCount()]) }}</span>
                    @elseif ($plan->hasPrimarySendingServer())
                        <span class="text-muted small">{{ trans('messages.plans.send_via.wording', ['server' => $plan->primarySendingServer()->getTypeName() ]) }} &bull; {{ trans('messages.plans.subscriber_count', ['count' => $plan->customersCount()]) }}</span>
                    @endif

                    @if ($plan->useSystemSendingServer() && !$plan->hasPrimarySendingServer())
                        <div class="text-muted"><span class="text-danger"><i class="fa fa-minus-circle"></i>
                            {{ trans('messages.plan.sending_server_empty') }}
                        </span></div>
                    @endif
                </td>
                <td>
                    <h5 class="no-margin text-bold kq_search">
                        {{ \Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}
                    </h5>
                    <span class="text-muted">{{ $plan->displayFrequencyTime() }}</span>
                </td>
                <td>
                    <h5 class="no-margin text-bold kq_search">
                        {{ $plan->displayTotalQuota() }}
                    </h5>
                    <span class="text-muted">{{ trans('messages.sending_total_quota_label') }}</span>
                </td>
                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $plan->status }}">{{ trans('messages.plan_status_' . $plan->status) }}</span>
                    </span>
                </td>
                <td class="text-end text-nowrap pe-0" width="5%">
                    @can('update', $plan)
                        <a href="{{ action('Admin\PlanController@general', $plan->uid) }}" role="button" class="btn btn-secondary btn-icon"> <span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}</a>
                    @endcan
                    @if (\Auth::user()->can('delete', $plan) ||
                         \Auth::user()->can('copy', $plan)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('copy', $plan)
                                    <li>
                                        <a
                                            href="{{ action('Admin\PlanController@copy', ['copy_plan_uid' => $plan->uid]) }}"
                                            class="dropdown-item copy-plan-link" title="{{ trans('messages.copy') }}"
                                        >
                                            <span class="material-symbols-rounded">
content_copy
</span> {{ trans('messages.copy') }}
                                        </a>
                                    </li>
                                  @endcan
                                @can('delete', $plan)
                                    <li>
                                        <a class="dropdown-item list-action-single"
                                            link-confirm-url="{{ action('Admin\PlanController@deleteConfirm', ['uids' => $plan->uid]) }}" href="{{ action('Admin\PlanController@delete', ['uids' => $plan->uid]) }}" title="{{ trans('messages.delete') }}" class="">
                                            <span class="material-symbols-rounded">
delete
</span> {{ trans('messages.delete') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $plans])
    

    <script>
        var PlanList = {
			copyPopup: null,

			getCopyPopup: function() {
				if (this.copyPopup === null) {
					this.copyPopup = new Popup();
				}

				return this.copyPopup;
			}
		}

        $(function() {
            $('.copy-plan-link').on('click', function(e) {
                e.preventDefault();			
                var url = $(this).attr('href');

                PlanList.getCopyPopup().load({
                    url: url
                });
            });

            $('.cant_show').click(function(e) {
                e.preventDefault();

                var confirm = `{{ trans('messages.plan.cant_show') }}`;
                var dialog = new Dialog('alert', {
                    message: confirm
                })
            });

            $('.enable-plan').click(function(e) {
                e.preventDefault();

                var confirm = `{{ trans('messages.plan.enable_and_visible.confirm') }}`;
                var href_yes = $(this).attr('href_yes');
                var href_no = $(this).attr('href_no');

                var dialog = new Dialog('yesno', {
                    message: confirm,
                    no: function(dialog) {
                        $.ajax({
                            url: href_no,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            statusCode: {
                                // validate error
                                400: function (res) {
                                    alert('Something went wrong!');
                                }
                            },
                            success: function (response) {
                                // notify
                                notify({
        type: 'success',
        title: '{!! trans('messages.notify.success') !!}',
        message: response.message
    });
                            }
                        });
                    },
                    yes: function(dialog) {                    
                        $.ajax({
                            url: href_yes,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            statusCode: {
                                // validate error
                                400: function (res) {
                                    alert('Something went wrong!');
                                }
                            },
                            success: function (response) {
                                // notify
                                notify({
        type: 'success',
        title: '{!! trans('messages.notify.success') !!}',
        message: response.message
    });
                            }
                        });
                    },
                });
            });
        });
    </script>
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <i class="material-symbols-rounded">
assignment_turned_in
</i>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <i class="material-symbols-rounded">
assignment_turned_in
</i>
        <span class="line-1">
            {{ trans('messages.plan_empty_line_1') }}
        </span>
    </div>
@endif
