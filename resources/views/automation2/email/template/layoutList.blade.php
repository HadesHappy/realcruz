@if ($templates->count() >0)
    <div class="row template-boxes mt-4">
        @foreach ($templates as $key => $template)
            <div class="col-xl-2 col-md-3 col-sm-4 col-xm-6 mb-3">
                <a href="javascript:;" class="select-template-layout template-choose" data-template="{{ $template->uid }}">
                    <div class="">
                        <div class="">
                            <img class="border rounded-3" width="100%" href="javascript:;" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                        </div>
                        <label class="mb-20 text-center d-block mt-1">{{ $template->name }}</label>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
        
    
@else
    <div class="empty-list">
        <i class="material-symbols-rounded">featured_play_list</i>
        <span class="line-1">
            {{ trans('messages.automation.email.empty_template_list') }}
        </span>
    </div>
@endif

<script>
    var builderSelectPopup;

    $('.template-choose').click(function(e) {
        e.preventDefault();
        
        var url = '{{ action('Automation2Controller@templateLayout', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}';
        var template_uid = $(this).attr('data-template');
        
        // loading popup
        popup.loading();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                template_uid: template_uid
            }
        }).always(function(response) {
            popup.load('{{ action('Automation2Controller@emailTemplate', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}');

            builderSelectPopup = new Popup({
                url: '{{ action('Automation2Controller@templateBuilderSelect', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}'
            });
            builderSelectPopup.load();

            // notify
            notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
        });
    });
</script>