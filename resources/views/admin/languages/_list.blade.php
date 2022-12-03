@if ($languages->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($languages as $key => $language)
			<tr>
				<td>
					<h5 class="m-0 text-bold">
						@can("delete", $language)
							<a class="kq_search d-block" href="{{ action('Admin\LanguageController@edit', $language->uid) }}">{{ $language->name }}</a>
						@else
							{{ $language->name }}
						@endcan
					</h5>
					<span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->admin->formatDateTime($language->created_at, 'date_full') }}</span>
				</td>
				<td>
					<span class="no-margin stat-num kq_search">{{ $language->code }}</span>
					<br />
					<span class="text-muted">{{ trans('messages.code') }}</span>
				</td>
				<td class="text-center">
					<span class="text-muted2 list-status">
						<span class="label label-flat bg-{{ $language->status }}">{{ trans('messages.language_status_' . $language->status) }}</span>
					</span>	
				</td>
				<td class="text-end">																					
					@can("translate", $language)
						<a href="{{ action('Admin\LanguageController@translateIntro', [
							"id" => $language->uid,
						]) }}" data-popup="tooltip" title="{{ trans('messages.translate') }}" role="button" class="btn btn-secondary btn-icon"><i class="icon-share2"></i> {{ trans('messages.translate') }}</a>
					@endcan
					@if(Auth::user()->can("delete", $language) ||
						Auth::user()->can("update", $language) ||
						Auth::user()->can("enable", $language) ||
						Auth::user()->can("disable", $language) ||
						Auth::user()->can("upload", $language) ||
						Auth::user()->can("download", $language)
					)
						<div class="btn-group">										
							<button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
							<ul class="dropdown-menu dropdown-menu-end">
								@can('enable', $language)
									<li>														
										<a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.enable_languages_confirm') }}" href="{{ action('Admin\LanguageController@enable', ["uids" => $language->uid]) }}">
											<span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}
										</a>
									</li>
								@endcan
								@can('disable', $language)
									<li>														
										<a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.disable_languages_confirm') }}" href="{{ action('Admin\LanguageController@disable', ["uids" => $language->uid]) }}">
											<span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}
										</a>
									</li>
								@endcan
								@can("download", $language)
									<li>
										<a class="dropdown-item" href="{{ action('Admin\LanguageController@download', $language->uid) }}" data-popup="tooltip" title="{{ trans('messages.download') }}"><span class="material-symbols-rounded">
file_download
</span> {{ trans('messages.download') }}</a>
									</li>
								@endcan
								@can("upload", $language)
									<li>
										<a class="dropdown-item" href="{{ action('Admin\LanguageController@upload', $language->uid) }}" data-popup="tooltip" title="{{ trans('messages.upload') }}"><span class="material-symbols-rounded">
file_upload
</span> {{ trans('messages.upload') }}</a>
									</li>
								@endcan
								@can("update", $language)
									<li>
										<a class="dropdown-item" href="{{ action('Admin\LanguageController@edit', $language->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}"><span class="material-symbols-rounded">
edit
</span> {{ trans('messages.edit') }}</a>
									</li>
								@endcan
								@can("delete", $language)
									<li>
										<a class="dropdown-item list-action-single" link-confirm-url="{{ action('Admin\LanguageController@deleteConfirm', ['uids' => $language->uid]) }}" href="{{ action('Admin\LanguageController@delete', ["uids" => $language->uid]) }}">
											<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
										</a>
									</li>
								@endcan
								</li>
							</ul>
						</div>
					@endcan
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', [
		'items' => $languages,
	])
	
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">flag</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else					
	<div class="empty-list">
		<span class="material-symbols-rounded">flag</span>
		<span class="line-1">
			{{ trans('messages.language_empty_line_1') }}
		</span>
	</div>
@endif