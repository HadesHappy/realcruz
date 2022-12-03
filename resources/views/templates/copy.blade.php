@extends('layouts.popup.small')

@section('title')
    {{ $template->name }}
@endsection

@section('content')
    <form id="copyTemplateForm" action="" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="">
        <input type="hidden" name="uids" value="">

        @foreach (request()->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'name',
            'value' => trans("messages.copy_of_template", ['name' => $template->name]),
            'label' => trans('messages.what_would_you_like_to_name_your_template'),
            'help_class' => 'template',
            'rules' => ['name' => 'required']
        ])


        <div class="text-end">
            <button type="submit"
                role="button"
                id="doCopyButton"
                class="btn btn-secondary me-1"
            >{{ trans('messages.copy') }}</button>
            <a role="button" class="btn btn-default" onclick="TemplatesList.getCopyPopup().hide()">
                {{ trans('messages.close') }}
            </a>
        </div>
    </form>

    <script>
        var TemplatesCopy = {
            action: '{{ action('TemplateController@copy', $template->uid) }}',
            copy: function(url, data) {
                TemplatesList.getCopyPopup().mask();
                addButtonMask($('#doCopyButton'));

                // copy
                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: 'success',
                        message: response,
                    });

                    TemplatesList.getCopyPopup().hide();
                    TemplatesIndex.getList().load();

                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for debugging
                    TemplatesList.getCopyPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    TemplatesList.getCopyPopup().unmask();
                    removeButtonMask($('#doCopyButton'));
                });
            }
        }

        $(function() {
            $('#copyTemplateForm').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                TemplatesCopy.copy(url, data);
            });
        });
    </script>
@endsection