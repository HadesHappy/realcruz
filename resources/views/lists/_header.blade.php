<div class="page-title">
	<ul class="breadcrumb breadcrumb-caret position-right">
		<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		<li class="breadcrumb-item"><a href="{{ action("MailListController@index") }}">{{ trans('messages.lists') }}</a></li>
	</ul>

	<div class="d-flex align-items-center my-4 pt-2">
		<h1 class="mb-0">
			<span class="text-semibold">{{ $list->name }}</span>
		</h1>
		<div class="ms-auto">
			<div class="btn-group" style="margin-top: -4px;">
				<button role="button" class="btn btn-light px-3 py-2 fw-600" data-bs-toggle="dropdown">
					{{ trans('messages.change_list') }} <span class="material-symbols-rounded ms-2">
						double_arrow
						</span>
				</button>
				<ul class="dropdown-menu">
					@forelse ($list->otherLists() as $l)
						<li>
							<a class="dropdown-item" href="{{ action('MailListController@overview', ['uid' => $l->uid]) }}">
								{{ $l->readCache('LongName', $l->name) }}
							</a>
						</li>
					@empty
						<li style="pointer-events:none;"><a href="#" class="dropdown-item">({{ trans('messages.empty') }})</a></li>
					@endforelse
				</ul>
			</div>
		</div>
			
	</div>
		
	<span class="badge badge-info bg-info-800 badge-big">{{ number_with_delimiter($list->readCache('SubscriberCount')) }}</span> {{ trans('messages.subscribers') }}
</div>
