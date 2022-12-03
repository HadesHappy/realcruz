@extends('layouts.popup.large')

@section('title')
    {{ trans('messages.webhooks') }}
@endsection

@section('content')
    <div class="d-flex">
        <p>{{ trans('messages.webhooks.wording') }}</p>
        <div class="ms-4">
            <a href="javascript:;" class="btn btn-secondary add_webhooks_but text-nowrap">
                <i class="material-symbols-rounded">add</i>
                {{ trans('messages.webhooks.add') }}
            </a>
        </div>
    </div>

    <div class="webhooks_list">
        
    </div>

    <script>
        var EmailWebhooks = {
            addPopup: null,
			getAddPopup: function() {
				if (this.addPopup == null) {
					this.addPopup = new Popup({
						url: '{{ action('Automation2Controller@webhooksAdd', [
							'email_uid' => $email->uid,
						]) }}'
					});
				}

				return this.addPopup;
			},

            loadList: function() {
                var box = new Box($('.webhooks_list'), '{{ action('Automation2Controller@webhooksList', [
                    'email_uid' => $email->uid,
                ]) }}');
                box.load();
            }
        };

        $(function() {
            //
            EmailWebhooks.loadList();

            // click add webhook button
            $('.add_webhooks_but').on('click', function(e) {
				e.preventDefault();

				EmailWebhooks.getAddPopup().load();
			});
        })
    </script>
@endsection