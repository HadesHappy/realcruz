@if ($forms->total())
    <a href="{{ action('FormController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        dashboard 
                        </span> {{ trans('messages.forms') }}
                </label>
            </div>
            <div>
                {{ $forms->count() }} / {{ $forms->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($forms as $form)
        <a href="{{ action('FormController@build', [
            'uid' => $form->uid,
        ]) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <img width="60px" height="" class="shadow-sm me-3 rounded" src="{{ $form->template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                </div>
                <div>
                    <label class="fw-600">
                        {{ $form->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0 text-nowrap">
                        {{ $form->mailList->name }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif