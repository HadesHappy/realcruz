@extends('layouts.core.backend')

@section('title', $customer->user->displayName())

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item"><a href="{{ action("Admin\CustomerController@index") }}">{{ trans('messages.customers') }}</a></li>
			<li class="breadcrumb-item active">{{ trans('messages.update') }}</li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                            person_outline
                            </span> {{ $customer->user->displayName() }}</span>
		</h1>
	</div>

@endsection

@section('content')
	@include('admin.customers._tabs')

    <div class="listing-form"
        sort-url="{{ action('Admin\SubscriptionController@sort') }}"
        data-url="{{ action('Admin\SubscriptionController@listing') }}"
        per-page="15"
    >
        
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                <div class="filter-box">
                    <input type="hidden" name="customer_uid" value="{{ $customer->uid }}" />
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
                            <select placeholder="{{ trans('messages.plan') }}"
                                class="select2-ajax"
                                name="plan_uid"
                                data-url="{{ action('Admin\PlanController@select2') }}">
                            </select>
                        </span>
                </div>
            </div>
        </div>

        <div class="pml-table-container">



        </div>
    </div>

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
