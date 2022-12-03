@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.update_page', ['name' => trans('messages.' . $layout->alias)]))

@section('head')      
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')

			@include("lists._header")

@endsection

@section('content')

				@include("lists._menu")

                <h2 class="my-4">{{ trans('messages.' . $layout->alias) }}</h2>

                @if ($layout->alias == 'sign_up_form')
                    <p class="alert alert-info mt-20 mb-20">{{ trans('messages.sign_up_form_url') }}<br /> <a target="_blank" href="{{ action('PageController@signUpForm', ['list_uid' => $list->uid]) }}" class="text-semibold">{{ action('PageController@signUpForm', ['list_uid' => $list->uid]) }}</a></p>
                @endif

                <form id="update-page" action="{{ action('PageController@update', ['list_uid' => $list->uid, 'alias' => $layout->alias]) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@if ($page->canHasOutsideUrl())
						<div class="form-group control-radio">
							<div class="radio_box" data-popup='tooltip' title="">
								<label class="main-control">
									<input
										{{ ($page->use_outside_url ? 'checked' : '') }}
										checked type="radio"
										name="use_outside_url"
										value="1" class="styled" /><rtitle> {{ trans('messages.form_page.use_outside_url') }}</rtitle>
									<div class="desc text-normal mb-10">
										{!! trans('messages.form_page.use_outside_url.intro') !!}
									</div>
								</label>
								<div class="radio_more_box">
									
									@include('helpers.form_control', [
										'type' => 'text',
										'name' => 'outside_url',
										'value' => $page->outside_url,
										'rules' => ['outside_url' => 'required'],
										'placeholder' => trans('messages.form_page.enter_outside_url'),
									])
									<div class="">
										<button type="submit" class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
									</div>
						
								</div>
							</div>
							<hr>
							<div class="radio_box" data-popup='tooltip' title="">
								<label class="main-control">
									<input type="radio"
										{{ (!$page->use_outside_url ? 'checked' : '') }}
										name="use_outside_url"
										value="0" class="styled" /><rtitle> {{ trans('messages.form_page.use_built_in_page') }}</rtitle>
									<div class="desc text-normal mb-10">
										{{ trans('messages.form_page.use_built_in_page.intro') }}
									</div>
								</label>
								<div class="radio_more_box">
									@include('pages._form')
									
									<hr />
									<div class="">
										<a page-url="{{ action('PageController@preview', ['list_uid' => $list->uid, 'alias' => $layout->alias]) }}" class="btn btn-info me-1 preview-page-button" data-toggle="modal" data-target="#preview_page"><span class="material-symbols-rounded">
visibility
</span> {{ trans('messages.preview') }}</a>
										<button type="submit" class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
									</div>
								</div>
							</div>
						</div>
					@else
						@include('pages._form')
						
						<hr />
						<div class="d-flex">
							<a page-url="{{ action('PageController@preview', ['list_uid' => $list->uid, 'alias' => $layout->alias]) }}"
								class="btn btn-info me-2 preview-page-button" data-toggle="modal" data-target="#preview_page"><span class="material-symbols-rounded">
visibility
</span> {{ trans('messages.preview') }}</a>

							<button type="submit" class="btn btn-secondary me-3"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
							<a href="{{ action('PageController@restoreDefault', [
								'list_uid' => $list->uid,
								'alias' => $layout->alias
							]) }}"
								link-method="POST"
								link-confirm="{{ trans('messages.page.restore.confirm') }}"
								class="btn btn-danger ms-auto">
								<span class="material-symbols-rounded">
									restart_alt
								</span> {{ trans('messages.page.reset_default') }}</a>
						</div>
					@endif
										
					
                </form>

				<script>
					var PagesUpdate = {
						previewPopup: null,

						getPreviewPopup: function() {
							if (this.previewPopup == null) {
								this.previewPopup = new Popup();
							}
							return this.previewPopup;
						},

						showPreviewPopup: function(callback) {
							this.getPreviewPopup().loadHtml(`
								<div class="modal-dialog shadow modal-fullscreen">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">
												<span class="material-symbols-rounded me-1">
visibility
</span> {{ trans('messages.' . $layout->alias) }}
											</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body text-center p-0">
											<iframe style="height: calc(100vh - 59px);
margin-bottom: -10px;" scrolling="yes" name="preview_page_frame" class="preview_page_frame" src="/"></iframe>
										</div>
									</div>
								</div>
							`, callback);
						}
					}
					$(function() {
						// page preview action
						$(".preview-page-button").on('click', function(e) {
							var url = $(this).attr('page-url');
							tinyMCE.triggerSave();
							var formData = new FormData($("#update-page")[0]);
							var frame = $('.preview_page_frame');
							var current_action = $("#update-page").attr("action");

							PagesUpdate.showPreviewPopup(function() {
								$("#update-page").attr('target', 'preview_page_frame');
								$("#update-page").attr('action', url);
								$("#update-page").submit();

								// after submit
								$("#update-page").removeAttr('target');
								$("#update-page").attr('action', current_action);
							});
						});
					});
				</script>

@endsection
