@if ($templates->total())
    <a href="{{ action('TemplateController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        web 
                        </span> {{ trans('messages.templates') }}
                </label>
            </div>
            <div>
                {{ $templates->count() }} / {{ $templates->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($templates as $template)
        <a href="{{ action('TemplateController@index', [
            'keyword' => $template->name,
        ]) }}" class="search-result border-bottom d-block">
            <div class="d-flex">
                <div>
                    <img width="40px" height="50px" class="shadow-sm me-3 rounded" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                </div>
                <div>
                    <label class="fw-600">
                        {{ $template->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0 text-nowrap">
                        {{ trans('messages.updated_at') }}: {{ Auth::user()->customer->formatDateTime($template->created_at, 'date_full') }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif