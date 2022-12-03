@extends('layouts.popup.medium')

@section('class') 
full-height
@endsection

@section('content')
                    
    @include('automation2._tabs_timeline', [
        'tab' => 'statistics'
    ])

    <div class="contacts-stats-boxes d-flex align-items-center" style="pointer-events: none;">
        <div class="contacts-stats-box" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'in_action',
        ]) }}')">
            <label class="{{ request()->type == 'in_action' ? 'text-warning' : '' }}">{{ number_with_delimiter($stats['involed']) }}</label>
            <div class="desc">{{ trans('messages.automation.box.contacts_in_action') }}</div>
        </div>
        <div class="contacts-stats-box" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'done',
        ]) }}')">
            <label class="{{ request()->type == 'done' ? 'text-warning' : '' }}">{{ number_to_percentage($stats['complete']) }}</label>
            <div class="desc">{{ trans('messages.automation.box.contacts_done') }}</div>
        </div>
        <div class="contacts-stats-box skipped" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'pending',
        ]) }}')">
            <label class="{{ request()->type == 'pending' ? 'text-warning' : '' }}">{{ number_with_delimiter($stats['pending']) }}</label>
            <div class="desc">{{ trans('messages.automation.box.contacts_skip_pending') }}</div>
        </div>
        <div class="contacts-stats-box error" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'error',
        ]) }}')">
            <label>0</label>
            <div class="desc">{{ trans('messages.automation.box.contacts_error') }}</div>
        </div>
    </div>

    <div class="insight-topine d-flex small align-items-center">
        <div class="insight-desc mr-auto pe-3">
            {!! trans('messages.automation.contacts_intro', ['count' => number_with_delimiter($count)])
            !!}
        </div>
        <div class="insight-action d-flex align-items-center">
            <div class="btn-group btn-group-sm" role="group">
                <button role="button" class="btn btn-secondary btn-sm mr-1 contacts-refresh-button">
                    {{ trans('messages.automation.update_stats') }}
                </button>
            </div>
            <div class="mr-1" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group btn-group-sm" role="group">
                    <button id="btnGroupDrop1" role="button" class="btn btn-secondary dropdown-toggle contacts-sort-title" data-value="{{ request()->sortBy ? request()->sortBy : 'auto_triggers.updated_at' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ trans('messages.timeline.sort.' . (request()->sortBy ? request()->sortBy : 'auto_triggers.updated_at')) }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item contacts-sort" href="#" data-sort="auto_triggers.updated_at">{{ trans('messages.timeline.sort.auto_triggers.updated_at') }}</a>
                        <a class="dropdown-item contacts-sort" href="#" data-sort="auto_triggers.created_at">{{ trans('messages.timeline.sort.auto_triggers.created_at') }}</a>
                        <a class="dropdown-item contacts-sort" href="#" data-sort="subscribers.created_at">{{ trans('messages.timeline.sort.subscribers.created_at') }}</a>
                    </div>
                </div>
            </div>
            @if ($subscribers->count())
                <div class="" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group btn-group-sm" role="group">
                    <button id="btnGroupDrop1" role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ trans('messages.automation.action') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item list-export-contacts"
                            href="{{ action('Automation2Controller@exportContacts', [
                                'uid' => $automation->uid,
                                'action_id' => request()->action_id,
                            ]) }}"
                        >{{ trans('messages.automation.export_this_list') }}</a>
                        <a class="dropdown-item list-copy-contacts-new-list"
                            href="{{ action('Automation2Controller@copyToNewList', [
                                'uid' => $automation->uid,
                                'action_id' => request()->action_id,
                            ]) }}"
                        >{{ trans('messages.automation.copy_to_new_list') }}</a>
                        <a class="dropdown-item list-tag-contacts"
                            href="{{ action('Automation2Controller@tagContacts', [
                                'uid' => $automation->uid,
                                'action_id' => request()->action_id,
                            ]) }}"
                        >{{ trans('messages.automation.tag_those_contacts') }}</a>
                    </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="input-with-icon mb-4">
        <i class="material-symbols-rounded">search</i>
        <input class="form-control mt-3" name="contact_keyword" placeholder='{{ trans('messages.automation.contacts.search.placeholder') }}' />
    </div>
        
    <div class="contacts_list ajax-list"></div>
        
    <script>
        var listContact = makeList({
            url: '{{ action('Automation2Controller@contactsList', [
                'uid' => $automation->uid,
                'action_id' => request()->action_id,
                'type' => request()->type,
            ]) }}',
            container: $('.contacts_list'),
            content: $('.contacts_list'),
            data: function() {
                return {
                    keyword: $('[name=contact_keyword]').val(),
                    sortBy: $('.contacts-sort-title').attr('data-value'),
                    sortOrder: 'desc',
                    per_page: 10
                };
            },
            method: 'GET'
        });
        listContact.load();
        
        // filters
        $('[name=contact_keyword]').keyup(function() {
            listContact.load();
        });

        // tag contacts
        var tagContact = new Popup(undefined, undefined, {
            onclose: function() {
                sidebar.load();
            }
        });
        $('.list-tag-contacts').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            tagContact.load(url, function() {
                console.log('Tag action type popup loaded!');				
            });
        });

        // export contacts
        $('.list-export-contacts').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            addMaskLoading('{{ trans('messages.automation.exporting_contacts') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                },
                statusCode: {
                    // validate error
                    400: function (res) {
                        alert('Something went wrong!');
                    }
                },
                success: function (response) {
                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});

                    // remove effects
                    removeMaskLoading();

                    popup.hide();
                }
            });
        });
        
        // copy contacts
        var copyContact = new Popup(undefined, undefined, {
            onclose: function() {
                sidebar.load();
            }
        });
        $('.list-copy-contacts-new-list').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            copyContact.load(url, function() {
                console.log('Copy to new list popup loaded!');				
            });
        });

        $('.contacts-sort').on('click', function(e) {
            e.preventDefault();
            var sortBy = $(this).attr('data-sort');
            var text = $(this).html();

            $('.contacts-sort-title').html(text);
            $('.contacts-sort-title').attr('data-value', sortBy);

            listContact.load();
        });
        
        $('.contacts-refresh-button').on('click', function(e) {
            e.preventDefault();

            listContact.load();
        });
    </script>
@endsection
