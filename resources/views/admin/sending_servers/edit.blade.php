@extends('layouts.core.backend')

@section('title', $server->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')
    @foreach ($notices as $n)
        @include('elements._notification', [
            'level' => 'warning',
            'title' => $n['title'],
            'message' => htmlspecialchars($n['message']),
        ])
    @endforeach

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\SendingServerController@index") }}">{{ trans('messages.sending_servers') }}</a></li>
            <li class="breadcrumb-item">{{ trans('messages.edit') }}</li>
        </ul>
        <h1>
            <div class="d-flex align-items-center">
                <span class="text-semibold me-3"><span class="material-symbols-rounded">
    edit
    </span>
                    {{ $server->name }}
                </span>
                    
                <span class="label label-flat bg-{{$server->status}}">{{$server->status}}</span>
            </div>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.sending_servers.form.' . $server->type, ['identities' => $identities, 'bigNotices' => $bigNotices])

    <script>
        var SendTestEmail = {
            popup: null,
            url: '{{ action('Admin\SendingServerController@test', $server->uid) }}',
    
            getPopup: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: this.url
                    });
                }
    
                return this.popup;
            }
        }
        $(function() {
            $('#SendTestEmailButton').on('click', function(e) {
                e.preventDefault();
    
                SendTestEmail.getPopup().load();
            });
        });
    </script>

    <script>
        $(function() {
            $('.test-connection-button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var url = $(this).attr('href');

                new Link({
                    type: 'ajax',
                    url: url,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    before: function() {
                        addButtonMask(button);
                    },
                    done: function(response) {
                        new Dialog('alert', {
                            title: LANG_SUCCESS,
                            message: response
                        });

                        removeButtonMask(button);
                    }
                });
            });

            var manager = new GroupManager();
            manager.add({
                editBox: $('#editServerForm .edit-group'),
                editButton: $('#editServerForm .edit-group .switch-form-toggle'),
                cancelBox: $('#editServerForm .cancel-group'),
                cancelButton: $('#editServerForm .cancel-group .switch-form-toggle'),
                form: $('#editServerForm'),
            });
            manager.bind(function(group) {
                group.cancelButton.on('click', function() {
                    group.form.find('input, select').prop('disabled', true);

                    group.editBox.removeClass('hide');
                    group.cancelBox.addClass('hide');
                });
                group.editButton.on('click', function() {
                    group.form.find('input, select').prop('disabled', false);

                    group.editBox.addClass('hide');
                    group.cancelBox.removeClass('hide');
                });
            });
        });
    </script>
@endsection
