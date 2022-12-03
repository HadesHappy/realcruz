                        @if (Auth::user()->admin->getPermission("setting_sending") == 'yes')
							<div class="tab-pane active" id="top-sending">
								<div class="row">
									<?php $count = 0; ?>
									@foreach ($settings as $name => $setting)
										@if ($setting['cat'] == 'sending')
											<div class="col-md-4">
												@if ($setting['type'] == 'checkbox')
													<div class="form-group checkbox-right-switch">
												@endif
													@include('helpers.form_control', [
														'type' => $setting['type'],
														'class' => (isset($setting['class']) ? $setting['class'] : "" ),
														'name' => $name,
														'value' => $setting['value'],
														'label' => trans('messages.' . $name),
														'help_class' => 'setting',
														'options' => (isset($setting['options']) ? $setting['options'] : "" ),
														'rules' => Acelle\Model\Setting::rules(),
													])
												@if ($setting['type'] == 'checkbox')
													</div>
												@endif
											</div>
											@if ($count%3 == 2)
								</div><div class="row">
											@endif
											<?php ++$count; ?>
										@endif
									@endforeach							
								</div>
								<br />
								<div class="text-left">
									<button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
								</div>
							</div>
						@endif