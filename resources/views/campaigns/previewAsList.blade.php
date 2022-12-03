@if ($subscribers->count() > 0)
    <div class="mb-4 copy-web-url-container">
        @foreach ($subscribers as $key => $subscriber)
            <?php

                $webViewerPreviewUrl = $campaign->generateWebViewerPreviewUrl($subscriber);

            ?>
            <div class=" d-flex py-4 border-bottom">
                <div class="me-4">
                    <img width="50px" height="50px"
                        class="rounded-circle border shadow-sm"
                        src="{{ (isSiteDemo() ? 'https://i.pravatar.cc/300?v=' . $key : action('SubscriberController@avatar',  $subscriber->uid)) }}"
                    />
                </div>
                <div class="">
                    <div>
                        <h5 class="mb-1 fw-600">{{ $subscriber->getFullName() }}</h5>
                        <p class="mb-2">
                            <a href="mailto:{{ $subscriber->email }}">
                                {{ $subscriber->email }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <div class="mb-1 small">{{ trans('messages.campaign.preview_as.web_view_url') }}:</div>
                        <div class="mb-0 d-flex align-items-center">
                            <input style="border-width:1px;" type="text" readonly class="form-control web-url-input readonly small" value="{!! $webViewerPreviewUrl !!}" />
                            <a style="height:34px" href="{!! $webViewerPreviewUrl !!}" target="_blank" class="btn btn-secondary btn-sm ms-2 text-nowrap d-flex align-items-center"
                                data-url="{!! $webViewerPreviewUrl !!}"
                            >
                                <i class="material-symbols-rounded me-1">open_in_new</i>{{ trans('messages.open') }}
                            </a>
                            <a style="height:34px" href="javascript:;" class="btn btn-light btn-sm ms-1 copy-web-view-url text-nowrap py-1 d-flex align-items-center"
                                data-url="{!! $webViewerPreviewUrl !!}"
                            >
                                <i class="material-symbols-rounded me-1">content_copy</i>{{ trans('messages.copy') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @include('elements/_per_page_select', ["items" => $subscribers])

    <script>
        var CampaignPreviewAsList = {
            copy: function(url) {
                copyToClipboard(url, $('.copy-web-url-container'));

                notify({
                    type: 'success',
                    message: '{{ trans('messages.preview_as.url.copied') }}'
                });
            }
        }

        $(function() {
            $('.copy-web-view-url').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('data-url');
                
                CampaignPreviewAsList.copy(url);
            });
        });
    </script>
    
@elseif (!empty(request()->keyword) || !empty(request()->filters))
    <div class="empty-list">
        <span class="material-symbols-rounded">
people
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
people
</span>
        <span class="line-1">
            {{ trans('messages.subscriber_empty_line_1') }}
        </span>
    </div>
@endif
