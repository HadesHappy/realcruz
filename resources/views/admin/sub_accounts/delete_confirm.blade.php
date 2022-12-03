{{-- @if (empty($account->subscriptions))
<p>{{ trans('messages.sub_account.currently_associated_subscriptions') }}</p>
<ul class="modern-listing">
    @foreach ($account->subscriptions as $subscription)
        <li>
            <i class="icon-quill4 text-danger"></i>
            <h4 class="text-danger">{{ $subscription->customer->user->displayName() }}</h4>
            <p>{{ $subscription->plan_name }} - {{ trans('messages.subscription.from_to_date', [
                'start' => Auth::user()->admin->formatDateTime($subscription->start_at, 'date_full'),
                'end' => Auth::user()->admin->formatDateTime($subscription->end_at, 'date_full'),
            ]) }}</p>
        </li>
    @endforeach
</ul>
@endif --}}
<p>{{ trans('messages.sub_account.sure_to_delete_subscription_to') }}</p>
