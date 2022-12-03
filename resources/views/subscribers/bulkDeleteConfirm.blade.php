@extends('layouts.popup.small')

@section('content')
    @if (count($subscribers))
        <p>{!! trans('messages.subscriber.bulk_delete.enter_emails.confirm', ['count' => count($subscribers)]) !!}</p>

        <form enctype="multipart/form-data" action="{{ action('SubscriberController@delete', $list->uid) }}" method="POST"
            class="bulk-delete-form form-validate-jqueryx"
        >
            {{ csrf_field() }}

            <ul class="subscriber-list">
                @foreach($subscribers as $subscriber)
                    <li class="d-flex align-items-center">
                        <input type='hidden' name="uids[]" value="{{ $subscriber->uid }}" />
                        <img class="avatar" src="{{ (isSiteDemo() ? 'https://i.pravatar.cc/300?v=' . $key : action('SubscriberController@avatar',  $subscriber->uid)) }}" />
                        <div>
                            <label>{{ $subscriber->email }}</label>
                            <p>{{ $subscriber->getFullName() }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="text-center">
                <button class="btn btn-primary bg-grey mt-4">{{ trans('messages.bulk_delete.ok_delete') }}</button>
            </div>
        </form>
    @else
        <p>{!! trans('messages.subscriber.bulk_delete.enter_emails.empty') !!}</p>

        <div class="text-center">
            <button onclick="bulkDeletePopup.back()" class="btn btn-primary bg-grey mt-4">{{ trans('messages.return_back') }}</button>
        </div>
    @endif

    <script>
        bulkDeletePopup.back = function() {
            $.ajax({
                url: '{{ action('SubscriberController@bulkDelete', $list->uid) }}',
                method: 'GET',
                data: {
                    emails: '{{ implode(' ', $emails) }}',
                },
                statusCode: {
                    // validate error
                    400: function (res) {
                        bulkDeletePopup.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    bulkDeletePopup.loadHtml(response);
                }
            });
        }

        $('.bulk-delete-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            addMaskLoading();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                statusCode: {
                    // validate error
                    400: function (res) {
                        bulkDeletePopup.loadHtml(res.responseText);
                    }
                },
                success: function (res) {
                    // hide tagContact
                    bulkDeletePopup.hide();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();

                    // load list
                    SubscribersIndex.getList().load();
                }
            });
        });
    </script>
@endsection