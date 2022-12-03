<?php
    $icons = [
        'warning' =>  'report_problem',
        'info' =>  'notifications_active',
        'danger' =>  'report',
        'error' =>  'report',
    ];

    if ($level == 'error') {
        $level = 'danger';
    }

    if (isset($params)) {
        $paramsString = implode(
            ' ',
            collect($params)
            ->map(function ($value, $key) {
                return "$key=\"$value\"";
            })
            ->values()->all()
        );
    }
?>
<div {!! $paramsString ?? '' !!} title="Debug: {{ isset($debugg) ? $debug : '[ empty ]' }}" class="alert alert-{{ $level }} shadow-sm" style="display: flex; flex-direction: row; align-items: center; cursor: pointer">
    <div>
        @isset($title)
            <h4 class="mb-0">{{ $title }}</h4>
        @endisset
        <p>{!! $message !!}</p>
    </div>
</div>
