@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.create_subscriber'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')
    @include("lists._menu")

    <div class="row mt-4">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="sub-section">
                <form enctype="multipart/form-data"  action="{{ action('SubscriberController@update', ['list_uid' => $list->uid, "uid" => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="list_uid" value="{{ $list->uid }}" />
                    <h2 class="font-weight-semibold">{{ $subscriber->getFullName() }}</h2>
                    <div class="tags mb-4" style="clear:both">
                        <span class="font-weight-semibold mr-3">{{ trans('messages.tags') }}:</span>
                        @if ($subscriber->getTags())
                            @foreach ($subscriber->getTags() as $tag)
                                <a href="{{ action('SubscriberController@removeTag', [
                                    'list_uid' => $subscriber->mailList->uid,
                                    'uid' => $subscriber->uid,
                                    'tag' => $tag,
                                ]) }}" class="btn-group remove-contact-tag" role="group" aria-label="Basic example">
                                    <button role="button" class="btn btn-light btn-tag font-weight-semibold">{{ $tag }}</button>
                                    <button role="button" class="btn btn-light btn-tag font-weight-semibold ml-0">
                                        <i class="material-symbols-rounded">
close
</i>
                                    </button>
                                </a>
                            @endforeach
                        @else
                            <a href="" class="btn-group profile-tag-contact" role="group" aria-label="Basic example">
                                <button role="button" class="btn btn-light btn-tag d-flex align-items-center">
                                    <i class="material-symbols-rounded me-2">add</i>
                                    <span class="font-italic">{{ trans('messages.automation.profile.click_to_add_tag') }}<span>
                                </button>
                            </a>
                        @endif
                    </div>
                    <div class="d-flex align-items-top">
                        <div>
                            @include('helpers._upload',['src' => (isSiteDemo() ? 'https://i.pravatar.cc/300' : action('SubscriberController@avatar',  $subscriber->uid)), 'dragId' => 'upload-avatar', 'preview' => 'image'])
                            
                            
                        </div>
                        <div class="mt-20">
                            <div class="dropdown">
                            <button class="btn btn-default bg-grey dropdown-toggle" role="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ trans('messages.subscribers.profile.action') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu1">
                                <li><a class="dropdown-item profile-remove-contact" href="#">{{ trans('messages.subscribers.profile.remove_subscriber') }}</a></li>
                                <li><a class="dropdown-item profile-tag-contact" href="#">{{ trans('messages.subscribers.profile.manage_tags') }}</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="clear-both">{{trans("messages.basic_information")}}</h3>
                    @include("subscribers._form")

                    <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>

                </form>
            </div>

            <div class="sub-section">
                <h3 class="text-semibold">{{ trans('messages.verification.title.email_verification') }}</h3>

                @if (is_null($subscriber->verification_status))
                    <p>{!! trans('messages.verification.wording.verify', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email) ]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@startVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}

                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'email_verification_server_id',
                            'value' => '',
                            'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                            'help_class' => 'verification',
                            'rules' => ['email_verification_server_id' => 'required'],
                            'include_blank' => trans('messages.select_email_verification_server')
                        ])
                        <div class="text-left">
                            <button class="btn btn-secondary me-2"> {{ trans('messages.verification.button.verify') }}</button>
                        </div>
                    </form>
                @elseif ($subscriber->isDeliverable())
                    <p>{!! trans('messages.verification.wording.deliverable', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email), 'at' => sprintf("<strong>%s</strong>", $subscriber->last_verification_at) ]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn btn-secondary me-2">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @elseif ($subscriber->isUndeliverable())
                    <p>{!! trans('messages.verification.wording.undeliverable', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email)]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn btn-secondary me-2">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @else
                    <p>{!! trans('messages.verification.wording.risky_or_unknown', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email), 'at' => sprintf("<strong>%s</strong>", $subscriber->last_verification_at), 'result' => sprintf("<strong>%s</strong>", $subscriber->verification_status)]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn btn-secondary me-2">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        @if(isSiteDemo())
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="d-flex align-items-top mt-5">
                    <h3 class="mr-auto">{{ trans('messages.automation.contact.activity_feed') }}</h3>
                    <div class="">
                        <div class="mt-10">
                            <div class="dropdown">
                            <button class="btn btn-default bg-grey dropdown-toggle" role="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ trans('messages.automation.contact.all_activities') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu1">
                                <li><a href="#">Open</a></li>
                                <li><a href="#">Click</a></li>
                                <li><a href="#">Subscribe</a></li>
                                <li><a href="#">Unsubscribe</a></li>
                                <li><a href="#">Updated</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="activity-feed mt-3">
                    <label class="date small font-weight-semibold mb-0 divider">Timeline</label>
                    
                    <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="material-symbols-rounded bg-primary">
forward_to_inbox
                            </i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup receives email entitled "Follow up Email"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="material-symbols-rounded me-1">
schedule
                                </i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="material-symbols-rounded bg-primary">
forward_to_inbox
                            </i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup receives email entitled "Welcome to our list"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="material-symbols-rounded me-1">
schedule
                                </i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="lnr lnr-clock bg-secondary"></i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                Wait for 24 hours before proceeding with the next event for user Ardella Goldrup
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="material-symbols-rounded me-1">
schedule
                                </i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="material-symbols-rounded bg-warning">call_split</i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup reads email entitled "Welcome email"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="material-symbols-rounded me-1">
schedule
                                </i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="material-symbols-rounded bg-success">
merge
</i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup subscribes to mail list, automation triggered!
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="material-symbols-rounded me-1">
schedule
                                </i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        var tagContact = new Popup();
        $('.profile-tag-contact').click(function(e) {
            e.preventDefault();

            var url = '{{ action('SubscriberController@updateTags', [
                'list_uid' => $subscriber->mailList->uid,
                'uid' => $subscriber->uid,
            ]) }}';

            tagContact.load(url, function() {
				console.log('Confirm action type popup loaded!');				
			});
        });

        $('.remove-contact-tag').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

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

                    location.reload();
                }
            });
        });

        $('.profile-remove-contact').click(function(e) {
            e.preventDefault();

            var confirm = '{{ trans('messages.subscriber.delete.confirm') }}';
            var url = '{{ action('SubscriberController@delete', [
                'list_uid' => $subscriber->mailList->uid,
                'uids' => $subscriber->uid,                
            ]) }}';

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

                            // redirect
                            addMaskLoading('{{ trans('messages.subscriber.deleted.redirect') }}', function() {
                                window.location = '{{ action('SubscriberController@index', [
                                    'list_uid' => $subscriber->mailList->uid
                                ]) }}';
                            });
                        }
                    });
                },
            });
        });
    </script>
@endsection
