@extends('layouts.popup.small')

@section('title')
<span class="material-symbols-rounded me-1 text-muted2">content_copy</span> {!! trans('messages.copy_list', [
        'name' => $list->name
    ]) !!}
@endsection

@section('content')
    <form id="copyListForm"
        action="{{ action('MailListController@copy', ['copy_list_uid' => $list->uid]) }}"
        method="POST">
        {{ csrf_field() }}          
            <p class="mb-4">{{ trans('messages.what_would_you_like_to_name_your_list') }}</p>

        @include('helpers.form_control', [
            'type' => 'text',
            'label' => '',
            'name' => 'name',
            'value' => request()->has('name') ? request()->name : trans("messages.copy_of_list", ['name' => $list->name]),
            'help_class' => 'list',
            'rules' => ['name' => 'required']
        ])

        <div class="mt-4 text-center">
            <button id="copyListButton" type="submit" class="btn btn-secondary px-3 me-2">{{ trans('messages.copy') }}</button>
            <button type="button" class="btn btn-link fw-600" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
        </div>
    </form>


    <script>
        var ListsCopy = {
            copy: function(url, data) {
                ListsList.getCopyPopup().mask();
                addButtonMask($('#copyListButton'));

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: 'success',
                        message: response,
                    });

                    ListsList.getCopyPopup().hide();
                    ListsIndex.getList().load();

                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for debugging
                    ListsList.getCopyPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    ListsList.getCopyPopup().unmask();
                    removeButtonMask($('#copyListButton'));
                });
            }
        }

        $(document).ready(function() {
            $('#copyListForm').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                ListsCopy.copy(url, data);
            });
        });
    </script>
@endsection