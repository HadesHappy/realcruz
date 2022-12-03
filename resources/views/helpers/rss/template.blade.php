<div>
    <div class="container py-3">
        <div class="text-center pt-4">
            @if ($templates['FeedTitle']['show'] == 'true')
                <h1 rss-item="FeedTitle" class="fw-normal fs-4 mt-2">{!! $rss['FeedTitle'] !!}</h1>
            @endif
            @if ($templates['FeedSubtitle']['show'] == 'true')
                <p  rss-item="FeedSubtitle">
                    {!! $rss['FeedSubtitle'] !!}
                </p>
            @endif
        </div>
    </div>
</div>

@if ($templates['FeedTagdLine']['show'] == 'true')
    <div>
        <div rss-item="FeedTagdLine" class="container py-3">
            <div class="border-bottom text-muted small"><span>{{ $rss['FeedTagdLine'] }}</span></div>
        </div>
    </div>
@endif

@foreach ($rss['items'] as $item)
    <div>
        <div class="container py-3 mb-3">
            @if ($templates['ItemTitle']['show'] == 'true')
                <h5 rss-item="ItemTitle" class="mb-2" style="font-size: 19px;font-weight: bold;">
                    {!! $item['ItemTitle'] !!}
                </h5>
            @endif
            @if ($templates['ItemMeta']['show'] == 'true')
                <div rss-item="ItemMeta" class="d-flex align-items-center my-2">
                    {!! $item['ItemMeta'] !!}
                </div>
            @endif
            <div class="d-flex">
                <div>
                    @if ($templates['ItemDescription']['show'] == 'true')
                        <p rss-item="ItemDescription" class="">
                            {!! $item['ItemDescription'] !!}
                        </p>
                    @endif
                    @if ($templates['ItemStats']['show'] == 'true')
                        <div rss-item="ItemStats">
                            <div class="d-flex align-items-center small">
                                {!! $item['ItemStats'] !!}
                            </div>
                        </div>
                    @endif
                </div>
                @if ($templates['ItemEnclosure']['show'] == 'true')
                    <div rss-item="ItemEnclosure" class="ml-auto ms-auto pl-4 ps-4">
                        {!! $item['ItemEnclosure'] !!}
                    </div>
                @endif                
            </div>
            
        </div>
    </div>
@endforeach




