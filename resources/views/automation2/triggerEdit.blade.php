@include('automation2._back')

<h4 class="mb-2 mt-4">
    {{ trans('messages.automation.trigger.' . $key) }}
</h4>
<p class="mb-10">
    {!! trans('messages.automation.trigger.' . $key . '.intro') !!}
</p>
<form id="trigger-select" action="{{ action("Automation2Controller@triggerEdit", ['uid' => $automation->uid, 'key' => $key]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <input type="hidden" name="options[key]" value="{{ $key }}" />
    
    @if(View::exists('automation2.trigger.' . $key))
        @include('automation2.trigger.' . $key)
    @endif
    
    <div class="trigger-action mt-2">
        @if (!in_array($key, ['say-goodbye-subscriber', 'welcome-new-subscriber', 'api-3-0']))
            <button class="btn btn-secondary trigger-save-change mr-1">
                {{ trans('messages.automation.trigger.save_change') }}
            </button>
        @endif

        <a href="javascript:;" class="btn btn-secondary change-trigger-but">
            {{ trans('messages.automation.trigger.change') }}
        </a>
    </div>
</form>
    
<script>
    $('#trigger-select').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var data = form.serialize();
        var url = form.attr('action');
        
        sidebar.loading();

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            globalError: false,
            statusCode: {
                // validate error
                400: function (res) {
                    sidebar.loadHtml(res.responseText);
                }
            },
            success: function (response) {
                // set node title
                tree.setTitle(response.title);
                // merge options with reponse options
                tree.setOptions(response.options);
                tree.setOptions($.extend(tree.getOptions(), {init: true}));
                
                // save tree
                saveData(function() {
                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                    
                    // reload sidebar
                    sidebar.load();

                    // re validate tree
                    tree.validate();
                });
            }
        });
    });

    $('.change-trigger-but').click(function() {
        OpenTriggerSelectPopup();
    });
</script>
