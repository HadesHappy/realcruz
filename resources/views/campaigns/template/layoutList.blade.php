@if ($templates->count() > 0)
    <div id="layout" class="row template-boxes mt-3">
        @foreach ($templates as $key => $template)
            <div class="col-xl-2 col-md-3 col-sm-4 col-xm-6 mb-3">
                <a 
                    href="{{ action('CampaignController@templateLayout', ['uid' => $campaign->uid, 'template' => $template->uid]) }}"
                    class="choose-theme select-template-layout"
                >
                    <div class="">
                        <div class="">
                            <div>
                                <div class="">
                                    <img class="border rounded-3" width="100%" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                                </div>
                                <label class="mt-1 text-center">{{ $template->name }}</label>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
        
    <hr>
        
    @include('elements/_per_page_select', ["items" => $templates])
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_template_available') }}
        </span>
    </div>
@endif
