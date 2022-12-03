<div class="row mt-5 pt-5">
    <div class="col-md-3">
        <div class="bg-color6 p-3 shadow rounded-3 text-white">
            <div class="text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ $campaign->uniqueOpenCount() }}</h2>
                <div class="text-muted2 text-white">{{ trans('messages.opened') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-color7 p-3 shadow rounded-3 text-white">
            <div class="text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ $campaign->clickCount() }}</h2>
                <div class="text-muted2">{{ trans('messages.clicked') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-secondary p-3 shadow rounded-3 text-white">
            <div class="text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ $campaign->bounceCount() }}</h2>
                <div class="text-muted2">{{ trans('messages.bounced') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-secondary p-3 shadow rounded-3 text-white">
            <div class="text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ $campaign->unsubscribeCount() }}</h2>
                <div class="text-muted2">{{ trans('messages.unsubscribed') }}</div>
            </div>
        </div>
    </div>
</div>