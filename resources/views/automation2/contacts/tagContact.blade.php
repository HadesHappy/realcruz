@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <form class="tag-contact" action="{{ action("Automation2Controller@tagContact", [
                    'uid' => $automation->uid,
                    'contact_uid' => $contact->uid,
                ]) }}"
                method="POST" class="form-validate-jqueryz"
            >
                {{ csrf_field() }}
                
                <h3 class="mb-3">{{ trans('messages.automation.profile.tag_contact') }}</h3>
                <p>{!! trans('messages.automation.profile.tag_contact.intro', [
                    'name' => $contact->getFullName(),
                ]) !!}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'select_tag',
                    'class' => '',
                    'label' => '',
                    'name' => 'tags[]',
                    'value' => $contact->getTags(),
                    'help_class' => 'trigger',
                    'options' => [],
                    'rules' => ['tags' => 'required'],
                    'multiple' => 'true',
                    'placeholder' => trans('messages.automation.contact.choose_tags'),
                ])

                <div class="mt-4">
                    <button class="btn btn-secondary">{{ trans('messages.automation.profile.tag') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('form.tag-contact').submit(function(e) {
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
                        popup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // hide tagContact
                    tagContact.hide();

                    // load popup
                    popup.load();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();
                }
            });    
        });
    </script>
@endsection
