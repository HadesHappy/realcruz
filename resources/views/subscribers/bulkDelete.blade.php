@extends('layouts.popup.small')

@section('content')
    <h2 class="mt-0 mb-4">{{ trans('messages.subscriber.bulk_delete') }}</h2>
    <p>{{ trans('messages.subscriber.bulk_delete.enter_emails') }}</p>

    <form enctype="multipart/form-data" action="{{ action('SubscriberController@bulkDelete', $list->uid) }}" method="POST"
        class="bulk-delete-form form-validate-jqueryx"
    >
        {{ csrf_field() }}

        @include('helpers.form_control', [
            'type' => 'textarea',
            'class' => 'bulk-delete',
            'name' => 'emails',
            'label' => '',
            'value' => request()->emails,
            'help_class' => 'subscriber',
            'rules' => ['emails' => 'required'],
        ])

        <div class="text-center">
            <button class="btn btn-primary bg-grey mt-4">{{ trans('messages.ok') }}</button>
        </div>
    </form>

    <script>
        bulkDeletePopup.back = function() {
            bulkDeletePopup.hide();
        }

        $('.bulk-delete-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
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
        });
    </script>
@endsection