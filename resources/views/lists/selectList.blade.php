@extends('layouts.popup.small')

@section('title')
    {{ request()->title ? request()->title : '' }}
@endsection

@section('content')
    <form class="select-list-form" action="{{ action('MailListController@selectList') }}" method="POST">
        {{ csrf_field() }}

        <input type="hidden" name="title" value="{{ request()->title }}" />
        <input type="hidden" name="redirect" value="{{ request()->redirect }}" />

        <p class="mb-2">
            @if (request()->message)
                {{ request()->message }}
            @else
                {{ trans('messages.select_list_default_intro') }}:
            @endif
        </p>

        @include('helpers.form_control', [
            'type' => 'select',
            'name' => 'list_uid',
            'include_blank' => '--',
            'label' => '',
            'value' => '',
            'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
            'rules' => [],
        ])

        <button type="submit" class="btn btn-secondary">{{ trans('messages.select_list.ok') }}</button>
    </form>

    <script>
        $(function() {
            $('.select-list-form').submit(function(e) {
                e.preventDefault();        

                var url = $(this).attr('action');
                var data = $(this).serialize();

                // 
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false,
                    statusCode: {
                        // validate error
                        400: function (res) {
                            console.log(res);
                            AudienceOverviewImportSelectList.getPopup().loadHtml(res.responseText);
                        }
                    },
                    success: function (response) {
                        addMaskLoading();
                        window.location = response.url;
                    }
                });
            });
        })
    </script>
@endsection