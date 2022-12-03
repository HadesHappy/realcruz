@extends('layouts.core.backend')

@section('title', trans('messages.notifications'))
	
@section('page_header')	
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item active">{{ trans('messages.notifications') }}</li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="material-symbols-rounded">message</i>  {{ trans('messages.all_notifications') }}</span>
		</h1>
	</div>				
@endsection

@section('content')
	
	@include("admin.account._menu")	

	<div class="listing-form"
		data-url="{{ action('Admin\NotificationController@listing') }}"
		per-page="20"				
	>				
		<div class="d-flex top-list-controls top-sticky-content">
			<div class="">			
				<div class="filter-box">
					<div class="checkbox inline check_all_list">
						<label>
							<input type="checkbox" name="page_checked" class="styled check_all">
						</label>
					</div>
					<div class="dropdown list_actions" style="display: none">
						<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
							{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li>
								<a link-method="POST" href="{{ action('Admin\NotificationController@delete') }}" class="dropdown-item"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a>
							</li>
						</ul>
					</div>
					<span class="filter-group">
						<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
						<select class="select" name="sort_order">
							<option value="created_at">{{ trans('messages.created_at') }}</option>
						</select>										
						<input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
							<span class="material-symbols-rounded desc">
sort
</span>
						</button>
					</span>
					<span class="filter-group ml-10">
						<span class="title text-semibold text-muted">{{ trans('messages.notification.level') }}</span>
						<select class="select" name="level">
							<option value="">{{ trans('messages.all') }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_INFO }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_INFO) }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_WARNING }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_WARNING) }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_ERROR }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_ERROR) }}</option>
						</select>										
					</span>
					<span class="text-nowrap">
						<input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
						<span class="material-symbols-rounded">
search
</span>
					</span>
				</div>
			</div>
		</div>
		
		<div class="pml-table-container">

		</div>
	</div>	

	<script>
        var NotificationsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\NotificationController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            NotificationsIndex.getList().load();
        });
    </script>
@endsection