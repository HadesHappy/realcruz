<ul class="nav nav-tabs nav-underline" id="pills-tab" role="tablist">
    @foreach (Acelle\Model\TemplateCategory::all() as $cat)
        <li class="nav-item">
            <a class="nav-link {{ request()->category_uid == $cat->uid ? 'active' : '' }} choose-template-tab"
                href="{{ action('CampaignController@templateLayout', [
                    'uid' => $campaign->uid,
                    'category_uid' => $cat->uid,
            ]) }}">
                {{ $cat->name }}
            </a>
        </li>
    @endforeach
    <li class="nav-item">
        <a class="nav-link {{ request()->from == 'mine' ? 'active' : '' }} choose-template-tab nav-link"
            href="{{ action('CampaignController@templateLayout', [
                'uid' => $campaign->uid,
                'from' => 'mine',
            ]) }}">{{ trans('messages.my_templates') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ actionName() == 'templateUpload' ? 'active' : '' }} choose-template-tab nav-link"
        href="{{ action('CampaignController@templateUpload', $campaign->uid) }}">
            {{ trans('messages.upload') }}
        </a>
    </li>
</ul>