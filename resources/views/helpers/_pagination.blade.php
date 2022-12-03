<pagination class="d-flex align-items-center">
    <label class="small">
        {!! trans('messages.pagination.page_of', [
            'pages' => $paginator->lastPage(),
            'page' => $paginator->currentPage(),
        ]) !!}        
    </label>
    @if ($paginator->lastPage() > 1)
        <ul class="pagination justify-content-end small align-items-center mb-0 ms-4 border-start">
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()-1) }}" tabindex="-1" aria-disabled="true" data-page="{{ $paginator->currentPage()-1 }}">{{ trans('messages.pagination.previous') }}</a>
            </li>

            @php
                $arr = [];

                if ($paginator->lastPage() > 7) {
                    if ($paginator->currentPage() < 3) {
                        for ($i = 0; $i <= $paginator->currentPage()+3; $i++) {
                            $arr[] = $i;
                        }
                    } else {
                        $arr[] = 0;
                        $arr[] = '...';
                        for ($i = $paginator->currentPage()-2; $i <= $paginator->currentPage()+2 && $i < $paginator->lastPage(); $i++) {
                            $arr[] = $i;
                        }
                    }
                    
                    if ($paginator->currentPage() < $paginator->lastPage() - 4) {
                        $arr[] = '...';
                    }
                    if ($paginator->currentPage() < $paginator->lastPage() - 3) {
                        $arr[] = $paginator->lastPage() - 1;
                    }
                    
                } else {
                    for ($i = 0; $i < $paginator->lastPage(); $i++) {
                        $arr[] = $i;
                    }
                }
            @endphp

            @foreach ($arr as $i)
                @if ($i !== '...')
                    @if ($i == $paginator->currentPage() - 1)
                        <li style="pointer-events: none" class="page-item active" aria-current="page">
                            <span class="page-link">
                                {{ $paginator->currentPage() }}
                                <span class="sr-only">(current)</span>
                            </span>
                        </li>                    
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($i+1) }}" data-page="{{ $i + 1 }}">{{ $i + 1 }}</a></li>
                    @endif
                @else
                    ...
                @endif
            @endforeach
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}" data-page="{{ $paginator->currentPage()+1 }}">{{ trans('messages.pagination.next') }}</a>
            </li>
        </ul>
    @endif
</pagination>