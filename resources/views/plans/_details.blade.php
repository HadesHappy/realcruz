<ul class="dotted-list topborder section">
    <li>
        <div class="unit size1of2">
            <strong>{{ trans('messages.plan_name') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag><strong>{{ $plan->name }}</strong></mc:flag>
        </div>
    </li>
    <li class="selfclear">
        <div class="unit size1of2">
            <strong>{{ trans('messages.price') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag><strong>{{ Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}</strong></mc:flag>
        </div>
    </li>
    <li class="selfclear">
        <div class="unit size1of2">
            <strong>{{ trans('messages.sending_quota_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayTotalQuota() }}</mc:flag>
        </div>
    </li>
    <li class="more">
        <a href="#more">{{ trans('messages.more_details') }}</a>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.max_lists_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayMaxList() }}</mc:flag>
        </div>
    </li>
        
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.max_subscribers_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayMaxSubscriber() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.max_campaigns_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayMaxCampaign() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.max_size_upload_total_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayMaxSizeUploadTotal() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.max_file_size_upload_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{{ $plan->displayFileSizeUpload() }}</mc:flag>
        </div>
    </li>
        
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.allow_create_sending_servers_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{!! $plan->displayAllowCreateSendingServer() !!}</mc:flag>
        </div>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.allow_create_sending_domains_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{!! $plan->displayAllowCreateSendingDomain() !!}</mc:flag>
        </div>
    </li>
    <li class="selfclear hide">
        <div class="unit size1of2">
            <strong>{{ trans('messages.allow_create_email_verification_servers_label') }}</strong>
        </div>
        <div class="lastUnit size1of2">
            <mc:flag>{!! $plan->displayAllowCreateEmailVerificationServer() !!}</mc:flag>
        </div>
    </li>
</ul>
