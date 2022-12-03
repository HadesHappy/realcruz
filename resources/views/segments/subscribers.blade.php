@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.subscribers'))

@section('page_header')

			@include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

	<h2 class="text-bold text-primary mb-10"><span class="material-symbols-rounded">
splitscreen
</span> {{ $segment->name }}</h2>
	<h3><span class="material-symbols-rounded">
people
</span> {{ trans('messages.subscribers') }}</h3>

	<div class="listing-form" id="SegmentsSubscribersContainer"
		data-url="{{ action('SegmentController@listing_subscribers', ['list_uid' => $list->uid, 'uid' => $segment->uid]) }}"
		per-page="{{ Acelle\Model\Subscriber::$itemsPerPage }}"
	>
		<div class="d-flex top-list-controls top-sticky-content">
			<div class="me-auto">
				@if ($subscribers->count() >= 0)
					<div class="filter-box">
						<div class="btn-group list_actions me-2" style="display:none">
							<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
								{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								<li>
									<a  link-method="POST" link-confirm="{{ trans('messages.subscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@subscribe', $list->uid) }}">
										<span class="material-symbols-rounded">
mark_email_read
</span> {{ trans('messages.subscribe') }}
									</a>
								</li>
								<li>
									<a link-confirm="{{ trans('messages.unsubscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@unsubscribe', $list->uid) }}">
										<span class="material-symbols-rounded">
logout
</span> {{ trans('messages.unsubscribe') }}
									</a>
								</li>
								<li>
									<a link-confirm="{{ trans('messages.delete_subscribers_confirm') }}" href="{{ action('SubscriberController@delete', $list->uid) }}">
										<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
									</a>
								</li>
							</ul>
						</div>
						<div class="checkbox inline check_all_list">
							<label>
								<input type="checkbox" name="page_checked" class="styled check_all">
							</label>
						</div>
						<span class="filter-group">
							<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
							<select class="select" name="sort_order">
								<option value="subscribers.created_at">{{ trans('messages.created_at') }}</option>
								<option value="subscribers.updated_at">{{ trans('messages.updated_at') }}</option>
								<option value="subscribers.email">{{ trans('messages.email') }}</option>
							</select>
							<input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
								<span class="material-symbols-rounded desc">
sort
</span>
							</button>
						</span>
						<span class="ms-1">
							<select class="select" name="status">
								<option value="">{{ trans('messages.all_subscribers') }}</option>
								<option value="subscribed">{{ trans('messages.subscribed') }}</option>
								<option value="unsubscribed">{{ trans('messages.unsubscribed') }}</option>
							</select>
						</span>
						<span class="filter-group ms-1">
							<select class="select" name="verification_result">
								<option value="">{{ trans('messages.all_verification') }}</option>
								@foreach (Acelle\Model\Subscriber::getVerificationStates() as $option)
									<option value="{{ $option['value'] }}">
										{{ $option['text'] }}
									</option>
								@endforeach
							</select>
						</span>
						<div class="btn-group list_columns me-2">
							<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
								{{ trans('messages.columns') }} <span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-end">
								@foreach ($list->getFields as $field)
									@if ($field->tag != "EMAIL")
										<li>
											<div class="checkbox">
												<label>
													<input checked="checked" type="checkbox" id="{{ $field->tag }}" name="columns_[]" value="{{ $field->uid }}" class="styled">
													{{ $field->label }}
												</label>
											</div>
										</li>
									@endif
								@endforeach
								<li>
									<div class="checkbox">
										<label>
											<input checked="checked" type="checkbox" id="created_at" name="columns_[]" value="created_at" class="styled">
											{{ trans('messages.created_at') }}
										</label>
									</div>
								</li>
								<li>
									<div class="checkbox">
										<label>
											<input checked="checked" type="checkbox" id="updated_at" name="columns_[]" value="updated_at" class="styled">
											{{ trans('messages.updated_at') }}
										</label>
									</div>
								</li>
							</ul>
						</div>
						<span class="text-nowrap">
							<input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
							<span class="material-symbols-rounded">
search
</span>
						</span>
					</div>
				@endif
			</div>
			<div class="text-end">
				<a href="{{ action("SubscriberController@create", $list->uid) }}" role="button" class="btn btn-secondary">
					<span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_subscriber') }}
				</a>
			</div>
		</div>

		<div class="pml-table-container" id="SegmentsSubscribersContent">



		</div>
	</div>

	<script>
		var SegmentsSubscribers = {
			getList: function() {
				return makeList({
					url: '{{ action('SegmentController@listing_subscribers', ['list_uid' => $list->uid, 'uid' => $segment->uid]) }}',
					container: $('#SegmentsSubscribersContainer'),
					content: $('#SegmentsSubscribersContent')
				});
			}
		};

		$(document).ready(function() {
			SegmentsSubscribers.getList().load();
		});
	</script>
@endsection
