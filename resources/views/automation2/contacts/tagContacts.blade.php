@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form class="tag-contacts" action="{{ action("Automation2Controller@tagContacts", $automation->uid) }}"
                method="POST" class="form-validate-jqueryz"
            >
                {{ csrf_field() }}

                <input type="hidden" name="action_id" value="{{ request()->action_id }}" />

                <h3 class="mb-3">{{ trans('messages.automation.profile.tag_contacts') }}</h3>
                <p>{!! trans('messages.automation.profile.tag_contacts.intro', [
                    'count' => number_with_delimiter($subscribers->count(), $precision = 0),
                ]) !!}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'select_tag',
                    'class' => '',
                    'label' => '',
                    'name' => 'tags[]',
                    'value' => [],
                    'help_class' => 'trigger',
                    'options' => [],
                    'rules' => ['tags' => 'required'],
                    'multiple' => 'true',
                    'placeholder' => trans('messages.automation.contact.choose_tags'),
                ])

                <div class="mt-4">
                    <button class="btn btn-secondary">{{ trans('messages.automation.profile.tag') }}</button>
                </div>
        </div>
    </div>
    
    <script>
        $('form.tag-contacts').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var data = form.serialize();
            var url = form.attr('action');
            
            addMaskLoading('{{ trans('messages.automation.contacts.tagging_contacts') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        tagContact.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // hide popup
                    tagContact.hide();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();

                    // reload sidebar
                    sidebar.load();
                }
            });    
        });
    </script>
@endsection
