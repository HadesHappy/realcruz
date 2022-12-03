@if ($list->getRunningVerificationJob()->isFailed())
    <div class="alert alert-danger alert-noborder">
        <button data-dismiss="alert" class="close" role="button"><span>×</span><span class="sr-only">Close</span></button>
        <strong>{{ trans('messages.verification.error.job_failed') }}: {{ $list->getRunningVerificationJob()->last_error }}</strong>
    </div>
@endif

@include('helpers._progress_bar', [
    'percent' => $list->getVerifiedSubscribersPercentage(true)
])

<p>{!! trans('messages.verification_process_running', [
    'verified' => $list->subscribers()->verified()->count(),
    'total' => number_with_delimiter($list->readCache('SubscriberCount'), $precision = 0),
]) !!}</p>

<p>
    <a class="btn btn-secondary-300"
        link-confirm="{{ trans('messages.stop_list_verification_confirm') }}" link-method="POST"
        href="{{ action("MailListController@stopVerification", $list->uid) }}">
        {{ trans('messages.verification.button.stop') }}
    </a>
</p>
