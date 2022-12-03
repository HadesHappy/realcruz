@if ($campaign->type != 'plain-text')
    {!! $campaign->getHtmlContent($subscriber, $message_id) !!}
@else
    {!! $campaign->getPlainContent($subscriber, $message_id) !!}
@endif