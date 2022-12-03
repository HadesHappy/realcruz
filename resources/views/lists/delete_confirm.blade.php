<p>{{ trans('messages.delete_list_confirm_warning') }}</p>
<ul class="modern-listing">
    @foreach ($lists->get() as $list)
        <li>
            <div class="d-flex">
                <span class="material-symbols-rounded fs-5 text-danger me-3" style="margin-top: -5px;">
                    error_outline
                </span>
                <div>
                    <h5 class="mb-1" class="text-danger">{{ $list->name }}</h5>
                    <p class="text-muted">
                        @if ($list->readCache('SubscriberCount', 0))
                            <span class="text-bold text-danger">{{ $list->readCache('SubscriberCount', 0) }}</span> {{ trans('messages.subscribers') }}<pp>,</pp>
                        @endif
                        @if ($list->segments()->count())
                            <span class="text-bold text-danger">{{ $list->segments()->count() }}</span> {{ trans('messages.segments') }}<pp>,</pp>
                        @endif
                        @if ($list->campaigns()->count())
                            <span class="text-bold text-danger">{{ $list->campaigns()->count() }}</span> {{ trans('messages.campaigns') }}<pp>,</pp>
                        @endif
                    </p>
                </div>
            </div>
        </li>
    @endforeach
</ul>
