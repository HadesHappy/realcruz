@if ($templates->count() > 0)
    <div id="layout" class="row template-boxes mt-4" style="
        margin-left: -20px;
        margin-right: -20px;
    ">
        @foreach ($templates as $key => $template)
            <div class="col-xl-2 col-md-3 col-sm-4 col-xm-6">
                <a href="javascript:;" class="select-template-layout mb-4 d-block" data-template="{{ $template->uid }}">
                    <div class="">
                        <div class="">
                            <div class="">
                                <img class="rounded border shadow-sm" width="100%" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                            </div>
                            <label class="mt-1 text-center">{{ $template->name }}</label>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
        
    <div style="clear:both" class="mt-4">
        @include('elements/_per_page_select', ["items" => $templates])
    </div>
    
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
            {{ trans('messages.template_empty_line_1') }}
        </span>
    </div>
@endif
