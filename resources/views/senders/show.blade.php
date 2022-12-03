@extends('layouts.core.frontend')

@section('title', $sender->name)
    
@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SenderController@index") }}">{{ trans('messages.verified_senders') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SendingDomainController@index") }}">{{ trans('messages.email_addresses') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.verified_senders') }}</span>
        </h1>        
    </div>
@endsection

@section('content')
    @include('senders._menu')

    <div class="row sub_section">

        <div class="col-sm-12 col-md-8">
            @if ($sender->isPending())
                <form action="{{ action('SenderController@show', ['sender' => $sender->uid ]) }}">
                    <div data-type="admin-notification" class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="mb-1">{{ trans('messages.sender.status_info.pending') }}</h4>
                                <p>{{ trans('messages.sender.status_info.pending.note') }}</p>
                            </div>
                            <button type="submit" class="btn btn-secondary text-nowrap">Refresh now!</button>
                        </div>
                    </div>
                </form>
            @elseif ($sender->isVerified())
                <div data-type="admin-notification" class="alert alert-success">
                    <div style="">
                        <h4 class="mb-1">{{ trans('messages.sender.status_info.verified') }}</h4>
                        <p>{{ trans('messages.sender.status_info.verified.note') }}</p>
                    </div>
                </div>
            @endif
            <h2>
                <span class="text-semibold"><span class="material-symbols-rounded">
                            person_outline
                            </span> {{ $sender->name }} </span>
                <span class="label label-primary bg-{{ $sender->status }}">
                    {{ trans('messages.sender.status.' . $sender->status) }}
                </span>
            </h2>
			<p>{{ trans('messages.sender.show.wording') }}</p>
            <ul class="dotted-list topborder section section-flex">
                <li>
                    <div class="unit size1of3">
                        <strong>{{ trans('messages.name') }}</strong>
                    </div>
                    <div class="size2of3">
                        <span>{{ $sender->name }}</span>
                    </div>
                </li>
                <li>
                    <div class="unit size1of3">
                        <strong>{{ trans('messages.email') }}</strong>
                    </div>
                    <div class="size2of3">
                        <span>{{ $sender->email }}</span>
                    </div>
                </li>
                <li>
                    <div class="unit size1of3">
                        <strong>{{ trans('messages.created_at') }}</strong>
                    </div>
                    <div class="size2of3">
                        <span>{{ Auth::user()->customer->formatDateTime($sender->created_at, 'date_full') }}</span>
                    </div>
                </li>
            </ul>
            
            <form enctype="multipart/form-data" action="{{ action('SenderController@verify', $sender->uid) }}" method="POST" class="form-vsalidate-jquery">
                {{ csrf_field() }}
                @if ($sender->status == Acelle\Model\Sender::STATUS_NEW)
                    <button class="btn btn-primary">{{ trans('messages.sending_domain.verify') }}</button>
                @endif
                <a href="{{ action('SenderController@edit', $sender->uid) }}" class="btn btn-primary bg-grey" style="min-width: 100px"><span class="material-symbols-rounded">
edit
</span> {{ trans('messages.sender.edit') }}</a>
            </form>
        </div>
    </div>
@endsection