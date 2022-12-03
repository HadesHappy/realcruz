<h4 class="text-semibold text-primary mb-0">{{ trans('messages.features') }}</h4>
<ul class="mt-0 mb-0 top-border-none plans-intro">
    <li>
        {!! trans('messages.sending_total_quota_intro', ["value" => $plan->displayTotalQuota()]) !!}
    </li>
    <li>
        {!! trans('messages.sending_quota_intro', ["value" => $plan->displayQuota()]) !!}
    </li>
    <li>
        {!! trans('messages.max_lists_intro', ["value" => $plan->displayMaxList()]) !!}
    </li>
    <li>
        {!! trans('messages.max_subscribers_intro', ["value" => $plan->displayMaxSubscriber()]) !!}
    </li>
    <li>
        {!! trans('messages.max_campaigns_intro', ["value" => $plan->displayMaxCampaign()]) !!}
    </li>
    <li>
        {!! trans('messages.max_size_upload_total_intro', ["value" => $plan->displayMaxSizeUploadTotal()]) !!}
    </li>
    <li>
        {!! trans('messages.max_file_size_upload_intro', ["value" => $plan->displayFileSizeUpload()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_sending_servers_intro', ["value" => $plan->displayAllowCreateSendingServer()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_sending_domains_intro', ["value" => $plan->displayAllowCreateSendingDomain()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_email_verification_servers_intro', ["value" => $plan->displayAllowCreateEmailVerificationServer()]) !!}
    </li>
</ul>
