@extends('layouts.core.backend')

@section('title', trans('messages.contact_information'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="breadcrumb-item active">{{ trans('messages.customer.sub_account') }}</li>
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
    <div class="sub-section">
        <h3 class="text-semibold">{{ trans('messages.customer.sub_account.title') }}</h3>

        <p>{{ trans('messages.customer.sub_account.wording') }}</p>

        <div class="row">
            <div class="col-md-10">
                <ul class="modern-listing big-icon no-top-border-list mt-0">
                    @foreach ($customer->subAccounts as $key => $account)
                        <li>
                            @if (Auth::user()->admin->can('delete', $account))
                                <a href="{{ action('Admin\SubAccountController@delete', $account->uid) }}"
                                    data-popup="tooltip" title="{{ trans('messages.subaccount.delete.tooltip') }}"
                                    role="button" class="btn btn-danger reload_page"
                                    link-method="delete"
                                    link-confirm-url="{{ action('Admin\SubAccountController@deleteConfirm', $account->uid) }}"
                                >
                                        <i class="icon-cross2"></i> {{ trans('messages.subaccount.delete') }}
                                </a>
                            @endcan
                            <div>
                                <span class="">
                                    <i class="icon-drive text-grey-800"></i>
                                </span>
                            </div>
                            <h4><span class="text-primary">{{ $account->username }}</span></h4>
                            <p>
                                {{ $account->sendingServer->name }} ({{ trans('messages.' . $account->sendingServer->type) }})
                            </p>
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>

@endsection
