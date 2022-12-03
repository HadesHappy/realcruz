@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.segments'))

@section('page_header')

			@include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

	<h2 class="text-primary my-4"><span class="material-symbols-rounded">
splitscreen
</span> {{ trans('messages.segments') }}</h2>
	<div id="SegmentsIndexContainer" class="listing-form"
		data-url="{{ action('SegmentController@listing', $list->uid) }}"
		per-page="{{ Acelle\Model\Segment::$itemsPerPage }}"
	>
		<div class="d-flex top-list-controls top-sticky-content">
			<div class="me-auto">
				@if ($list->segmentsCount() >= 0)
					<div class="filter-box">
						<div class="checkbox inline check_all_list">
							<label>
								<input type="checkbox" name="page_checked" class="styled check_all">
							</label>
						</div>
						<div class="btn-group list_actions me-2" style="display:none">
							<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
								{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li>
									<a class="dropdown-item" link-confirm="{{ trans('messages.delete_segments_confirm') }}" href="{{ action('SegmentController@delete', $list->uid) }}">
										<span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}
									</a>
								</li>
							</ul>
						</div>
						<span class="filter-group">
							<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
							<select class="select" name="sort_order">
								<option value="segments.created_at">{{ trans('messages.created_at') }}</option>
								<option value="segments.name">{{ trans('messages.name') }}</option>
								<option value="segments.updated_at">{{ trans('messages.updated_at') }}</option>
							</select>
							<input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
								<span class="material-symbols-rounded desc">
sort
</span>
							</button>
						</span>
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
				<a href="{{ action("SegmentController@create", $list->uid) }}" role="button" class="btn btn-secondary">
					<span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_segment') }}
				</a>
			</div>
		</div>

		<div id="SegmentsIndexContent" class="pml-table-container">



		</div>

	</div>

	<script>
        var SegmentsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('SegmentController@listing', $list->uid) }}',
                    container: $('#SegmentsIndexContainer'),
                    content: $('#SegmentsIndexContent')
                });
            }
        };

        $(document).ready(function() {
            SegmentsIndex.getList().load();
        });
    </script>

@endsection
