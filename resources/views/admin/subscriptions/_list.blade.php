@if ($subscriptions->count() > 0)
	<table class="table table-box pml-table table-log"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($subscriptions as $key => $subscription)
			<tr>
				<td width="1%">
					@switch($subscription->status)
						@case(Acelle\Model\Subscription::STATUS_ACTIVE)
							<i class="material-symbols-rounded fs-4 me-2 text-success">
settings_backup_restore
							</i>
							@break
						@case(Acelle\Model\Subscription::STATUS_NEW)
							<i class="material-symbols-rounded fs-4 me-2 text-warning">
								add_circle_outline
							</i>
							@break
						@default
							<i class="material-symbols-rounded fs-4 me-2 text-muted">
remove_circle_outline
							</i>
					@endswitch
				</td>
				<td>
					<h5 class="m-0 text-bold">
						<span class="kq_search d-block" href="#">
							{{ $subscription->plan->name }}
						</span>
					</h5>
					<div class="text-muted">{!! trans('messages.subscribed_by', [
						'name' => $subscription->customer->user->displayName(),
						'customer_link' => action('Admin\CustomerController@edit', $subscription->customer->uid)
					]) !!}</div>
				</td>
				<td width="15%">
                    <h5 class="no-margin stat-num">
                        <span class="kq_search">{{ Auth::user()->admin->formatDateTime($subscription->created_at, 'date_full')}}</span>
                    </h5>
                    <span class="text-muted2">{{ trans('messages.subscribed_on') }}</span>
                </td>				
				<td width="15%">
					@if ($subscription->isEnded())
						<h5 class="no-margin stat-num">
								<span class="kq_search">{{ Auth::user()->admin->formatDateTime($subscription->ends_at, 'date_full') }}</span>
							</h5>
						<span class="text-muted2">{{ trans('messages.subscription.subscription_ended_at') }}</span>
					@elseif ($subscription->cancelled())
						<h5 class="no-margin stat-num">
							@if ($subscription->current_period_ends_at)
								<span class="kq_search">{{ $subscription->current_period_ends_at->timezone($currentTimezone)->diffForHumans() }}</span>
							@else
								<span class="kq_search">--</span>
							@endif
						</h5>
						<span class="text-muted2">{{ trans('messages.subscription.subscription_end') }}</span>
					@elseif ($subscription->isRecurring())
						<h5 class="no-margin stat-num">
							<span class="kq_search">
								@if ($subscription->current_period_ends_at)
									{{ $subscription->current_period_ends_at->timezone($currentTimezone)->diffForHumans() }}
								@else
									--
								@endif									
							</span>
						</h5>
						<span class="text-muted2">{{ trans('messages.subscription.next_billing') }}</span>
					@endif
				</td>
				<td class="text-center">
					@switch($subscription->status)
						@case(Acelle\Model\Subscription::STATUS_ACTIVE)
							<span style="cursor:pointer" href="{{ action('Admin\SubscriptionController@invoices', ['id' => $subscription->uid]) }}"
								class="view_invoices label label-flat bg-{{ $subscription->status }}"
							>
								{{ trans('messages.subscription.status.active') }}
							</span>

							@if ($subscription->getUnpaidInvoice() && $subscription->getUnpaidInvoice()->getPendingTransaction() && $subscription->getUnpaidInvoice()->getPendingTransaction()->allowManualReview())
								<div style="cursor:pointer"
									class="text-warning mini"
								>
									{{ trans('messages.subscription.status.pending_for_approval') }}
								</div>	
							@endif

							@break
						@case(Acelle\Model\Subscription::STATUS_NEW)
							@if ($subscription->getUnpaidInvoice())
								@if ($subscription->getUnpaidInvoice()->getPendingTransaction() && $subscription->getUnpaidInvoice()->getPendingTransaction()->allowManualReview())
									<span style="cursor:pointer" href="{{ action('Admin\SubscriptionController@invoices', ['id' => $subscription->uid]) }}"
										class="view_invoices label bg-m-warning"
									>
										{{ trans('messages.subscription.status.pending_for_approval') }}
									</span>	
								@else
									<span style="cursor:pointer" href="{{ action('Admin\SubscriptionController@invoices', ['id' => $subscription->uid]) }}"
										class="view_invoices label bg-m-warning"
									>
										{{ trans('messages.subscription.status.wait_for_payment') }}
									</span>	
								@endif
							@endif
							
							@break
						@default
							<span style="cursor:pointer" href="{{ action('Admin\SubscriptionController@invoices', ['id' => $subscription->uid]) }}"
								class="view_invoices label bg-{{ $subscription->status }}"
							>
								{{ trans('messages.subscription.status.' . $subscription->status) }}
							</span>
					@endswitch
                </td>
				<td class="text-end">
					@if (\Auth::user()->admin->can('approve', $subscription))
						<a link-method="POST" link-confirm="{{ trans('messages.subscription.approve.confirm') }}"
							href="{{ action('Admin\SubscriptionController@approve', $subscription->uid) }}"
							class="btn btn-primary bg-teal-800 list-action-single"
						>
							{{ trans('messages.subscription.approve') }}
						</a>
						<span class="text-muted">|</span>
					@endif
					
					@if (\Auth::user()->admin->can('cancel', $subscription))
						<a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel.confirm') }}"
						  href="{{ action('Admin\SubscriptionController@cancel', $subscription->uid) }}" class="btn btn-secondary list-action-single">
							{{ trans('messages.subscription.cancel') }}
						</a>
					@endif
					@if (\Auth::user()->admin->can('resume', $subscription))
						<a link-method="POST" link-confirm="{{ trans('messages.subscription.resume.confirm') }}"
						  href="{{ action('Admin\SubscriptionController@resume', $subscription->uid) }}" class="btn btn-secondary list-action-single">
							{{ trans('messages.subscription.resume') }}
						</a>
					@endif
					@if(
						\Auth::user()->admin->can('cancelNow', $subscription) ||
						\Auth::user()->admin->can('invoices', $subscription) ||
						\Auth::user()->admin->can('approve', $subscription) ||
						\Auth::user()->admin->can('delete', $subscription) ||
						\Auth::user()->admin->can('rejectPending', $subscription)
					)
						<div class="btn-group">
							
							<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
							<ul class="dropdown-menu dropdown-menu-end">
								@if (\Auth::user()->admin->can('approvePending', $subscription))
									<li>
										<a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.subscription.set_active.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@approvePending', $subscription->uid) }}">
											{{ trans('messages.subscription.approve_pending') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('rejectPending', $subscription))
									<li>
										<a
											class="dropdown-item rejectPending"
											{{-- link-confirm="{{ trans('messages.subscription.reject_pending.confirm') }}" --}}
										  	href="{{ action('Admin\SubscriptionController@rejectPending', $subscription->uid) }}"
										>
											{{ trans('messages.subscription.reject_pending') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('invoices', $subscription))
									<li>
										<a class="dropdown-item view_invoices"
										  href="{{ action('Admin\SubscriptionController@invoices', ['id' => $subscription->uid]) }}">
											{{ trans('messages.subscription.logs') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('cancelNow', $subscription))
									<li>
										<a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.subscription.cancel_now.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@cancelNow', $subscription->uid) }}">
											{{ trans('messages.subscription.cancel_now') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('delete', $subscription))
									<li>
										<a class="dropdown-item list-action-single" link-method="DELETE" link-confirm="{{ trans('messages.subscription.delete.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@delete', ['id' => $subscription->uid]) }}">
											{{ trans('messages.subscription.delete') }}
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
	@include('elements/_per_page_select', ["items" => $subscriptions])
	

	<script>        
        $(function() {
            $('.rejectPending').click(function(e) {
                e.preventDefault();

                var src = $(this).attr('href');
				
				rejectPendingSub = new Popup();
                rejectPendingSub.load(src);
            });
        });

		var invoices = new Popup();
		$('.view_invoices').click(function(e) {
			e.preventDefault();
			invoices.load($(this).attr('href'));
		});
    </script>

@elseif (!empty(request()->keyword) || !empty(request()->filters))
	<div class="empty-list">
		<span class="material-symbols-rounded">
assignment_turned_in
</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">
assignment_turned_in
</span>
		<span class="line-1">
			{{ trans('messages.subscription_empty_line_1_admin') }}
		</span>
	</div>
@endif
