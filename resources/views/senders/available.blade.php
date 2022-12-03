@extends('layouts.core.frontend')

@section('title', trans('messages.verified_senders'))

@section('page_header')
<div class="row mc_section boxing">
	<div class="col-md-{{ (null !== Session::get('orig_customer_id') && Auth::user()->customer) ? '6' : '6' }}">
		<div class="page-title">
			<ul class="breadcrumb breadcrumb-caret position-right">
				<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				<li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
				<li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.email_addresses') }}</a></li>
			</ul>
			<h1>
				<span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
			</h1>    
		</div>
		<p>{{ trans('messages.sender.available.intro') }}</p>
		<table class="table table-box table-box-head field-list">
			<thead>
				<tr>
					<td>{{ trans('messages.domain') }}</td>
					<td>{{ trans('messages.status') }}</td>
				</tr>
			</thead>
			<tbody>
				@foreach ($identities as $domain)
					<tr class="odd">
						<td>
							{{ $domain }}
						</td>
						<td>
							<span class="label label-flat bg-active">{{ trans('messages.sending_identity.status.active') }}</span>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@if ((null !== Session::get('orig_customer_id') && Auth::user()->customer) || Auth::user()->admin)
		<div class="col-md-6">
			@include('quicktip.identity')
		</div>
	@endif
</div>
    
@endsection

@section('content')
@endsection
