@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h3 class="mr-auto mb-4">{{ trans('messages.automation.contact.profile') }}</h3>
                <div class="">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="btnGroupDrop1" role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('messages.automation.profile.action') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item profile-remove-contact"
                                    href="{{ action('Automation2Controller@removeContact', [
                                        'uid' => $automation->uid,
                                        'contact_uid' => $contact->uid,
                                    ]) }}"
                                    data-confirm="{{ trans('messages.automation.profile.remove_contact.confirm', ['name' => $contact->getFullName()]) }}"
                                >
                                    {{ trans('messages.automation.profile.remove_contact') }}
                                </a>
                                <a class="dropdown-item profile-tag-contact"
                                    href="{{ action('Automation2Controller@tagContact', [
                                        'uid' => $automation->uid,
                                        'contact_uid' => $contact->uid,
                                    ]) }}"
                                >{{ trans('messages.automation.profile.manage_tag') }}</a>
                                <a class="dropdown-item" href="{{ action('SubscriberController@edit', [
                                    'list_uid' => $contact->mailList->uid,
                                    'uid' => $contact->uid,
                                ]) }}">{{ trans('messages.automation.profile.go_to_pofile') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile d-flex">
                <div class="media mr-4">
                    <!--<i class="lnr lnr-user"></i>-->
                    @if ($contact->avatar)
                        <img src="{{ action('SubscriberController@avatar',  $contact->uid) }}" />
                    @elseif(isSiteDemo())
                        <img src="https://i.pravatar.cc/300" />
                    @else
                        <i style="opacity: 0.7" class="lnr lnr-user bg-{{ rand_item(['info', 'success', 'secondary', 'primary', 'danger', 'warning']) }}"></i>
                    @endif
                </div>
                <div class="account">
                    <h5 class="mb-0">{{ $contact->getFullName() }}</h5>
                    <p class="small mb-2">{{ $contact->email }}</p>
                    <div class="tags mt-3">
                        @if ($contact->getTags())
                            @foreach ($contact->getTags() as $tag)
                                <a href="{{ action('Automation2Controller@removeTag', [
                                    'uid' => $automation->uid,
                                    'contact_uid' => $contact->uid,
                                    'tag' => $tag,
                                ]) }}" class="btn-group remove-contact-tag" role="group" aria-label="Basic example">
                                    <button role="button" class="btn btn-light btn-tag">{{ $tag }}</button>
                                    <button role="button" class="btn btn-light btn-tag">
                                        <i class="material-symbols-rounded">
close
</i>
                                    </button>
                                </a>
                            @endforeach
                        @else
                            <a href="{{ action('Automation2Controller@tagContact', [
                                        'uid' => $automation->uid,
                                        'contact_uid' => $contact->uid,
                                    ]) }}" class="btn-group profile-tag-contact" role="group" aria-label="Basic example">
                                <button role="button" class="btn btn-light btn-tag d-flex align-items-center">
                                    <i class="material-symbols-rounded">add</i>
                                    <span class="font-italic">{{ trans('messages.automation.profile.click_to_add_tag') }}<span>
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            
            <div class="d-flex align-items-center mt-5">
                <h3 class="mr-auto">{{ trans('messages.automation.contact.activity_feed') }}</h3>
                <div class="">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="btnGroupDrop1" role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('messages.automation.contact.all_activities') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="#">Open</a>
                                <a class="dropdown-item" href="#">Click</a>
                                <a class="dropdown-item" href="#">Subscribe</a>
                                <a class="dropdown-item" href="#">Unsubscribe</a>
                                <a class="dropdown-item" href="#">Updated</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="activity-feed mt-3">
                <label class="date small font-weight-semibold mb-0 divider">{{ trans('messages.automation.profile_timeline_head') }}</label>
                
                @if ($automation->timelinesBy($contact)->count() > 0)
                    @foreach($automation->timelinesBy($contact)->get() as $timeline)
                        <div class="activity d-flex py-3 px-4">
                            <div class="activity-media pr-4 text-center">
                                <time class="d-block text-center mini mb-2">{{ $timeline->created_at->diffForHumans() }}</time>
                                {!! Acelle\Model\AutomationElement::getIconByType($timeline->activity_type) !!}
                            </div>
                            <div class="small">
                                <action class="d-block font-weight-semibold mb-1">
                                    {{ $timeline->activity }}
                                </action>
                                <desc class="d-block small text-muted">
                                    <span class="material-symbols-rounded me-1">
schedule
</span> {{ Auth::user()->customer->formatDateTime($timeline->created_at, 'date_full') }}
                                </desc>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-list">
                        <i class="material-symbols-rounded">timeline</i>
                        <span class="line-1">
                            {{ trans('messages.automation.timeline.no_activities') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        $('.profile-remove-contact').click(function(e) {
            e.preventDefault();

            var confirm = $(this).attr('data-confirm');
            var url = $(this).attr('href');

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function(dialog) {                    
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

                            popup.hide();
                        }
                    });
                },
            });
        });

        var tagContact = new Popup(undefined, undefined, {
            onclose: function() {
                sidebar.load();
            }
        });
        $('.profile-tag-contact').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            tagContact.load(url, function() {
                console.log('Confirm action type popup loaded!');                
            });
        });
        
        $('.remove-contact-tag').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            popup.loading();

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

                    // reload popup
                    popup.load();
                }
            });
        });
    </script>
@endsection