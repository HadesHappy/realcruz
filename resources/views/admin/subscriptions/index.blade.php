@extends('layouts.core.backend')

@section('title', trans('messages.subscriptions'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.subscriptions') }}</span>
		</h1>
	</div>

@endsection

@section('content')
	<p>{{ trans('messages.subscription.wording') }}</p>
    <form class="listing-form"
        sort-url="{{ action('Admin\SubscriptionController@sort') }}"
        data-url="{{ action('Admin\SubscriptionController@listing') }}"
        per-page="15"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($subscriptions->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <!--<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>-->
                            <select class="select" name="sort_order">
                                <option value="subscriptions.updated_at">{{ trans('messages.updated_at') }}</option>
                                <option value="subscriptions.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="subscriptions.ends_at">{{ trans('messages.ends_at') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-symbols-rounded desc">
sort
</span>
                            </button>
                        </span>
                        <span class="me-2 input-medium">
                            <select placeholder="{{ trans('messages.customer') }}"
                                class="select2-ajax"
                                name="customer_uid"
                                data-url="{{ action('Admin\CustomerController@select2') }}">
                            </select>
                        </span>
                        <span class="me-2 input-medium">
                            <select placeholder="{{ trans('messages.plan') }}"
                                class="select2-ajax"
                                name="plan_uid"
                                data-url="{{ action('Admin\PlanController@select2') }}">
                                    @if ($plan)
                                        <option value="{{ $plan->uid }}">{{ $plan->name }}</option>
                                    @endif
                            </select>
                        </span>
                    </div>
                @endif
            </div>
            @if (\Auth::user()->admin->can('create', new Acelle\Model\Subscription()))
                <div class="text-end">
                    <a href="{{ action("Admin\SubscriptionController@create") }}" role="button"
                        class="btn btn-secondary modal-action new-subscription"
                    >
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.subscription.new') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container">
        </div>
    </form>

    <script>
        var newSubscription = new Popup(); 
		$('.new-subscription').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			
			newSubscription.load(url);
		});

        // reject subscription modal
		var rejectPendingSub;
    </script>

    <script>
        var SubscriptionsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\SubscriptionController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(document).ready(function() {
            SubscriptionsIndex.getList().load();
        });
    </script>
@endsection
