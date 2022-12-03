@extends('layouts.core.frontend_dark')

@section('title', trans('messages.automation.create'))

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/automation.css') }}">
    <script type="text/javascript" src="{{ URL::asset('core/js/automation.js') }}"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"></link>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-1">
            <h4 class="my-0 me-2 automation-title">{{ $automation->name }}</h4>
            <i class="material-symbols-rounded">alarm</i>
        </div>
    </li>
@endsection

@section('page_header')
	<main class="container px-3 pt-4">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ action("Automation2Controller@index") }}">{{ trans('messages.automations') }}</a></li>
			<li class="breadcrumb-item">{{ $automation->name }}</li>
		</ul>

		<h1>
			<span class="text-semibold">{{ trans('messages.subscribers') }}</span>
		</h1>
	</main>
@endsection

@section('menu_right')
    <li class="d-flex align-items-center">
        <div class="d-flex align-items-center me-4 automation-top-actions">
            <span class="me-4"><i class="last_save_time" data-url="{{ action('Automation2Controller@lastSaved', $automation->uid) }}">{{ trans('messages.automation.designer.last_saved', ['time' => $automation->updated_at->diffForHumans()]) }}</i></span>
            <a href="{{ action('Automation2Controller@index') }}" class="action me-4">
                <i class="material-symbols-rounded me-2">arrow_back</i>
                {{ trans('messages.automation.go_back') }}
            </a>

            <div class="switch-automation d-flex">
                <select class="select select2 top-menu-select" name="switch_automation">
                    <option value="--hidden--"></option>
                    @foreach($automation->getSwitchAutomations(Auth::user()->customer)->get() as $auto)
                        <option value='{{ action('Automation2Controller@edit', $auto->uid) }}'>{{ $auto->name }}</option>
                    @endforeach
                </select>

                <a href="javascript:'" class="action">
                    <i class="material-symbols-rounded me-2">
            horizontal_split
            </i>
                    {{ trans('messages.automation.switch_automation') }}
                </a>
            </div>
        </div>
    </li>

    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
	<main class="container px-3">
		<div class="listing-form"
			data-url="{{ action('Automation2Controller@subscribersList', $automation->uid) }}"
			per-page="{{ Acelle\Model\Subscriber::$itemsPerPage }}"
		>
			<div class="d-flex top-list-controls top-sticky-content">
				<div class="me-auto">
					<div class="filter-box">
						<span class="me-2">
							<div class="mr-2">
								@include('helpers.select_tool', [
									'disable_all_items' => false
								])
							</div>
							<div class="btn-group list_actions me-2" style="display:none">
								<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
									{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li>
										<a class="dropdown-item"  link-method="POST" link-confirm="{{ trans('messages.subscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@subscribe', $list->uid) }}">
											<span class="material-symbols-rounded">
mark_email_read
</span> {{ trans('messages.subscribe') }}
										</a>
									</li>
									<li>
										<a class="dropdown-item" link-method="POST" link-confirm="{{ trans('messages.unsubscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@unsubscribe', $list->uid) }}">
											<span class="material-symbols-rounded">
logout
</span> {{ trans('messages.unsubscribe') }}
										</a>
									</li>
									<li>
										<a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.subscribers.resend_confirmation_email.confirm') }}" href="{{ action('SubscriberController@resendConfirmationEmail', $list->uid) }}">
											<span class="material-symbols-rounded">
mark_email_read
</span> {{ trans('messages.subscribers.resend_confirmation_email') }}
										</a>
									</li>
									<li>
										<a href="#" class="dropdown-item copy_move_subscriber"
											data-url="{{ action('SubscriberController@copyMoveForm', [
												'from_uid' => $list->uid,
												'action' => 'copy',
											]) }}">
												<span class="material-symbols-rounded">
	copy_all
	</span> {{ trans('messages.copy_to') }}
										</a>
									</li>
									<li>
										<a href="#move" class="dropdown-item copy_move_subscriber"
											data-url="{{ action('SubscriberController@copyMoveForm', [
												'from_uid' => $list->uid,
												'action' => 'move',
											]) }}">
											<span class="material-symbols-rounded">
exit_to_app
</span> {{ trans('messages.move_to') }}
										</a>
									</li>
									<li>
										<a class="dropdown-item list-action-single" link-confirm="{{ trans('messages.delete_subscribers_confirm') }}" href="{{ action('SubscriberController@delete', $list->uid) }}">
											<span class="material-symbols-rounded">
	delete_outline
	</span> {{ trans('messages.delete') }}
										</a>
									</li>
									<li>
										<a href="{{ action('SubscriberController@bulkDelete', $list->uid) }}"
											class="dropdown-item bulk-delete">
											<span class="material-symbols-rounded">
	delete_outline
	</span> {{ trans('messages.subscriber.bulk_delete') }}
										</a>
									</li>
								</ul>
							</div>
						</span>
						<span class="filter-group">
							<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
							<select class="select" name="sort_order">
								<option value="subscribers.email">{{ trans('messages.email') }}</option>
								<option value="subscribers.created_at">{{ trans('messages.created_at') }}</option>
								<option value="subscribers.updated_at">{{ trans('messages.updated_at') }}</option>
							</select>
							<input type="hidden" name="sort_direction" value="asc" />
                                                <button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
								<span class="material-symbols-rounded desc">
	sort
	</span>
							</button>
						</span>
						<span class="me-2">
							<select class="select" name="status">
								<option value="">{{ trans('messages.all_subscribers') }}</option>
								<option value="subscribed">{{ trans('messages.subscribed') }}</option>
								<option value="unsubscribed">{{ trans('messages.unsubscribed') }}</option>
								<option value="unconfirmed">{{ trans('messages.unconfirmed') }}</option>
								<option value="spam-reported">{{ trans('messages.spam-reported') }}</option>
								<option value="blacklisted">{{ trans('messages.blacklisted') }}</option>
							</select>
						</span>
						<span class="filter-group ml-10">
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
													<input {{ (true ? "checked='checked'" : "") }} type="checkbox" id="{{ $field->tag }}" name="columns_[]" value="{{ $field->uid }}" class="styled">
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
				</div>
			</div>

			<div class="pml-table-container">



			</div>
		</div>
		<!-- Footer -->
		@include('layouts.core._footer')
	</main>

	<script>
        var SubscribersIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Automation2Controller@subscribersList', $automation->uid) }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            SubscribersIndex.getList().load();
        });
    </script>
	
	<script>
		var bulkDeletePopup = new Popup();

		$(document).on('click', '.bulk-delete', function(e) {
			e.preventDefault();

			var url = $(this).attr('href');
			
			bulkDeletePopup.load(url);
		});
		
		var assignValues;
		$(document).on('click', '.assign-values-button', function(e) {
			e.preventDefault();

        	var data = SubscribersIndex.getList().data();

			var url = $(this).attr('href');
			
			assignValues = new Popup();
			assignValues.load({
				url: url,
				data: data
			});
		});

		// Copy Move subscribers
		var copyMovePopup;
		$(document).on('click', '.copy_move_subscriber', function() {
			var url = $(this).attr('data-url');
			var data = {};
			// Data list action
			if ($(this).parents('.list_actions').length) {
				var form = $(this).parents(".listing-form");
				var vals = form.find("input[name='uids[]']:checked").map(function () {
					return this.value;
				}).get();

				data = {
					uids: vals.join(",")
				};

				// select_tool
				var select_tool = '';
				if (form.find('.select_tool').length && form.find('.select_tool').val() == 'all_items') {
					select_tool = form.find('.select_tool').val();
					arr = form.serializeArray();
					for (var i = 0; i < arr.length; i++){
						data[arr[i]['name']] = arr[i]['value'];
					}
				}

				if (form.find('.select_tool').length) {
					data.select_tool = form.find('.select_tool').val();
				}

			}

			copyMovePopup = new Popup({
				url: url,
				data: data
			});
			copyMovePopup.load();
		});

		// Ajax copy list
		$(document).on('submit', '#copy-move-subscribers-form form', function(e) {
			e.preventDefault(); // avoid to execute the actual submit of the form.

			var form = $(this);
			var url = form.attr("action");

			addMaskLoading();

			$.ajax({
				type: "POST",
				url: url,
				data: form.serialize()
			}).done(function(msg) {
				if(msg != '') {
					new Dialog('alert', {
						message: msg
					});
				}
				copyMovePopup.hide();

				SubscribersIndex.getList().load();

				removeMaskLoading();
			});

			copyMovePopup.hide();
			
		});
	</script>
@endsection
