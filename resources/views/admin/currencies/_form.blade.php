					<div class="row">						
						<div class="col-md-4">
							<div class="">
								
								@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $currency->name, 'help_class' => 'currency', 'rules' => $currency->rules()])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">

								@include('helpers.form_control', ['type' => 'text', 'name' => 'code', 'value' => $currency->code, 'help_class' => 'currency', 'rules' => $currency->rules()])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">

								@include('helpers.form_control', [
									'type' => 'text',
									'name' => 'format',
									'label' => trans('messages.currency_format'),
									'value' => $currency->format ? $currency->format : '{PRICE}',
									'help_class' => 'currency',
									'rules' => $currency->rules()
								])
								
							</div>
						</div>		
					</div>
					
					<hr />
					<div class="text-end">
						<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>