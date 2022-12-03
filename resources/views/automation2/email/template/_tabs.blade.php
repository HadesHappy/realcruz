<ul class="nav nav-tabs email-template-tabs nav-underline" id="pills-tab" role="tablist">
    @foreach (Acelle\Model\TemplateCategory::all() as $cat)
        <li class="nav-item">
            <a class="nav-link {{ request()->category_uid == $cat->uid ? 'active' : '' }}" href="javascript:;"
                data-href="{{ action('Automation2Controller@templateLayout', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
                'category_uid' => $cat->uid,
            ]) }}">
                {{ $cat->name }}
            </a>
        </li>
    @endforeach
    <li class="nav-item">
        <a class="nav-link {{ request()->from == 'mine' ? 'active' : '' }} nav-link" href="javascript:;"
            data-href="{{ action('Automation2Controller@templateLayout', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
                'from' => 'mine',
            ]) }}">{{ trans('messages.my_templates') }}</a>
    </li>
    <li class="nav-item">
        <a class="choose-template-tab nav-link {{ actionName() == 'templateUpload' ? 'active' : '' }}" href="javascript:;" data-href="{{ action('Automation2Controller@templateUpload', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}">{{ trans('messages.upload') }}</a></li>
</ul>

<script>
    $('.email-template-tabs .nav-link').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('data-href');
        
        popup.load(url);
    });
</script>