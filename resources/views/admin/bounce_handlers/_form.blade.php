					<div class="row">
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'name',
								'value' => $server->name,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'host',
								'value' => $server->host,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'port',
								'value' => $server->port,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'email',
								'value' => $server->email,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'username',
								'value' => $server->username,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'password',
								'value' => $server->password,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'select',
								'class' => '',
								'name' => 'protocol',
								'value' => $server->protocol,
								'options' => Acelle\Model\BounceHandler::protocolSelectOptions(),
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'select',
								'class' => '',
								'name' => 'encryption',
								'value' => $server->encryption,
								'options' => Acelle\Model\BounceHandler::encryptionSelectOptions(),
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
					</div>
					<hr>
					<div class="text-left">
						@can('test', $server)
                            <a
                                href="{{ action('Admin\BounceHandlerController@test', $server->uid) }}"
                                role="button"
                                class="btn btn-primary me-1 test-button"
								mask-title="{{ trans('messages.bounce_handler.testing_connection') }}"
                            >
                                <span class="material-symbols-rounded">
quiz
</span> {{ trans('messages.bounce_handler.test') }}
                            </a>
                        @endcan
						<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>

					<script>
						$(function() {
							$('.test-button').on('click', function(e) {
								e.preventDefault();
								var url = $(this).attr('href');
					
								addMaskLoading();
					
								new Link({
									type: 'ajax',
									url: url,
									method: 'POST',
									done: function(response) {
										new Dialog('alert', {
											message: response.message,
										});
					
										removeMaskLoading();
									},
									data: {
										_token: CSRF_TOKEN
									}
								});
							});
								
						});
					</script>
