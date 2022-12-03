<ul class="nav nav-tabs nav-underline mb-1 campaign-steps" role="tablist">
    <li class="nav-item">
        <a class="nav-link fs-6 {{ $current == 1 ? "active" : "" }} me-0" href="{{ action('CampaignController@recipients', $campaign->uid) }}">
            <span class="material-symbols-rounded">
people
</span> {{ trans('messages.recipients') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 0 ? "" : "disabled" }}">
        <span class="material-symbols-rounded mx-3 text-muted2">
            arrow_forward_ios
        </span>
        <a class="nav-link fs-6 {{ $current == 2 ? "active" : "" }} me-0" href="{{ action('CampaignController@setup', $campaign->uid) }}">
            <span class="material-symbols-rounded">
settings
</span> {{ trans('messages.setup') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 1 ? "" : "disabled" }}">
        <span class="material-symbols-rounded mx-3 text-muted2">
            arrow_forward_ios
        </span>
        <a class="nav-link fs-6 {{ $current == 3 ? "active" : "" }} me-0" href="{{ action('CampaignController@template', $campaign->uid) }}">
            <span class="material-symbols-rounded">
auto_awesome_mosaic
</span> {{ trans('messages.template') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 2 ? "" : "disabled" }}">
        <span class="material-symbols-rounded mx-3 text-muted2">
            arrow_forward_ios
        </span>
        <a class="nav-link fs-6 {{ $current == 4 ? "active" : "" }} me-0" href="{{ action('CampaignController@schedule', $campaign->uid) }}">
            <span class="material-symbols-rounded">
schedule
</span> {{ trans('messages.schedule') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 3 ? "" : "disabled" }} me-0">
        <span class="material-symbols-rounded mx-3 text-muted2">
            arrow_forward_ios
        </span>
        <a class="nav-link fs-6 {{ $current == 5 ? "active" : "" }}" href="{{ action('CampaignController@confirm', $campaign->uid) }}">
            <span class="material-symbols-rounded">
task_alt
</span> {{ trans('messages.confirm') }}
        </a>
    </li>
</ul>