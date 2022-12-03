@if ($sending_servers->total())
    <a href="{{ action('Admin\SendingServerController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-symbols-rounded me-1">
                        fact_check 
                        </span> {{ trans('messages.sending_servers') }}
                </label>
            </div>
            <div>
                {{ $sending_servers->count() }} / {{ $sending_servers->total() }} · {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($sending_servers as $item)
        @php
            $server = $item->mapType();
        @endphp

        @if ($server->isExtended())
            <a href="{{ $server->getEditUrl() }}" class="search-result border-bottom d-block">
        @else
            <a href="{{ action('Admin\SendingServerController@edit', ["id" => $item->uid, "type" => $item->type]) }}" class="search-result border-bottom d-block">
        @endif

            <div class="d-flex align-items-center">
                <div>
                    @if ($server->isExtended())
                        <span class="mc-server-avatar shadow-sm rounded server-avatar" style="background: url({{ $server->getIconUrl() }}) top left/36px 36px no-repeat transparent;">
                            <span class="material-symbols-rounded">

</span>
                        </span>
                    @else
                        <span class="server-avatar shadow-sm rounded server-avatar-{{ $item->type }} mr-0">
                            <span class="material-symbols-rounded">

</span>
                        </span>
                    @endif
                </div>
                <div>
                    <label class="fw-600 text-nowrap">
                        {{ $item->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0 text-nowrap">
                        @if ($server->isExtended())
                            <span class="">{{ $server->getTypeName() }}</span>
                        @else
                            <span class="">{{ trans('messages.' . $item->type) }}</span>
                        @endif
                    </p>
                </div>
            </div>
                
        </a>
    @endforeach
@endif