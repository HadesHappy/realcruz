@extends('layouts.core.frontend')

@section('title', trans('messages.stores_connections'))

@section('page_header')
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SourceController@index") }}">{{ trans('messages.stores_connections') }}</a></li>
		</ul>
	</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="source-header d-flex">
                <div class="source-logo mr-30">
                    <img src="{{ url('images/' . $source->type . '_list.png') }}" />
                </div>
                <div class="source-desc">
                    <h1 class="mt-0 mb-2">{{ $source->getName() }}</h1>
                    <p class="m-0 mb-2">
                        {{ trans('messages.source.intro.' . $source->type) }}
                    </p>
                    <div class="text-muted">
                        {{ trans('messages.source.connected_on', [
                            'date' => Auth::user()->customer->formatDateTime($source->created_at, 'date_full'),
                        ]) }}
                        |
                        <a href="">{{ trans('messages.source.visit_store') }}</a>
                        |
                        <a href="">{{ trans('messages.source.disconnect') }}</a>
                    </div>
                </div>
                <div class="source-action ml-auto">
                    @if (\Acelle\Model\Source::where('id', '!=', $source->id)->count())
                        <div class="dropdown">
                            <button class="btn btn-mc_outline dropdown-toggle" role="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{ trans('messages.source.switch_store') }}
                            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu1">
                                @foreach (\Acelle\Model\Source::where('id', '!=', $source->id)->get() as $source)
                                    <li><a href="{{ action('SourceController@show', $source->uid) }}">{{ $source->getName() }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex my-4 py-4 align-items-center">
                <div class="mr-auto pr-4" style="width: 500px">
                    <h4 class="font-weight-semibold mb-1">{{ trans('messages.store.connected_with_acelle') }}</h4>
                    <p>{{ trans('messages.store.connected_with_acelle.desc') }}</p>
                </div>
                <div class="text-right">
                    <a href="" class="btn btn-secondary">{{ trans('messages.source.refresh') }}</a>
                </div>
            </div>

            <div class="source-stats">
                <div class="source-desc-line pt-4 mb-4 d-flex">
                    <div class="mr-4">
                        <span class="material-symbols-rounded" style="font-size:24px">
                            inventory_2
                            </span>
                    </div>
                    <div class="desc">
                        <h5 class="mt-0 mb-1 text-primary">{!! trans('messages.source.have_products', [
                            'count' => number_with_delimiter($source->productsCount(), $precision = 0),
                        ]) !!}</h5>
                        <div class="">{{ trans('messages.source.your_store_synchronized') }}</div>
                    </div>
                    <div class="desc-action ml-auto">
                        <a href="{{ action('ProductController@index', [
                            'source_uid' => $source->uid,
                        ]) }}" class="btn btn-primary">{{ trans('messages.source.products.manage') }}</a>
                    </div>
                </div>
                <div class="source-desc-line pt-4 my-4 d-flex" style="justify-content: space-between">
                    <div class="d-flex">
                        <div class="mr-4">
                            <span class="material-symbols-rounded" style="font-size:24px">
                                fact_check
                                </span>
                        </div>
                        <div class="desc">
                            <h5 class="mt-0 mb-1 text-primary">
                                {{ $source->getList()->name }}
                            </h5>
                            <div class="">{{ trans('messages.source.list.synchronized_to_this') }}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <h5 class="no-margin text-primary stat-num mb-1 me-3">
                                {{ number_with_delimiter($source->getList()->readCache('SubscriberCount', 0)) }}
                            </h5>
                            <span class="text-muted2">{{ trans("messages." . Acelle\Library\Tool::getPluralPrase('subscriber', $source->getList()->readCache('SubscriberCount', 0))) }}</span>
                        </div>
                        <div class="me-3">
                            <h5 class="no-margin text-primary stat-num mb-1 me-3">
                                {{ $source->getList()->readCache('UniqOpenRate', 0) }}%
                            </h5>
                            <span class="text-muted2">{{ trans('messages.open_rate') }}</span>
                        </div>
                        <div>
                            <h5 class="no-margin text-primary stat-num mb-1 me-3">
                                {{ $source->getList()->readCache('ClickedRate', 0) }}%
                            </h5>
                            <span class="text-muted2">{{ trans('messages.click_rate') }}</span>
                        </div>
                    </div>
                    <div class="desc-action">
                        <a href="{{ action('MailListController@overview', $source->mailList->uid) }}" class="btn btn-primary">{{ trans('messages.source.products.manage') }}</a>
                    </div>
                </div>
                @if ($automation->getAbandonedCartEmail()->isSetup())
                    <div class="source-desc-line pt-4 my-4 d-flex">
                        <div class="mr-4">
                            <span class="material-symbols-rounded" style="font-size:24px">
                                mark_email_unread
                                </span>
                        </div>
                        <div class="desc">
                            <h5 class="mt-0 mb-1 text-primary">{{ trans('messages.source.abandoned_cart_email') }}
                                <span class="label label-flat bg-active ml-2">{{ trans('messages.active') }}</span></h5>
                            
                            <div class="">{{ trans('messages.source.abandoned_cart_email.all_setup') }}</div>
                            <div class="email-rates my-4 d-flex">
                                <div class="email-rate mr-4 pr-3">
                                    <div class="rate-value display-4 text-muted">0.0%</div>
                                    <div class="rate-desc text-muted2">{{ trans('messages.rate.opens') }}</div>
                                </div>
                                <div class="email-rate mr-4 pr-3">
                                    <div class="rate-value display-4 text-muted">0.0%</div>
                                    <div class="rate-desc text-muted2">{{ trans('messages.rate.clicks') }}</div>
                                </div>
                                <div class="email-rate mr-4 pr-3">
                                    <div class="rate-value display-4 text-muted">0.0%</div>
                                    <div class="rate-desc text-muted2">{{ trans('messages.rate.send') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="desc-action ml-auto">
                            <a href="javascript:;" class="btn btn-secondary launch-automation">{{ trans('messages.edit') }}</a>
                        </div>
                    </div>
                @else
                    <div class="source-desc-line pt-4 my-4 d-flex">
                        <div class="mr-4">
                            <span class="material-symbols-rounded" style="font-size:24px">
                                mark_email_unread
                                </span>
                        </div>
                        <div class="desc">
                            <h5 class="mt-0 mb-1 text-primary">{{ trans('messages.source.abandoned_cart_email') }}</h5>
                            <div class="">{{ trans('messages.source.abandoned_cart_email.desc') }}</div>
                        </div>
                        <div class="desc-action ml-auto">
                            <a href="javascript:;" class="btn btn-secondary launch-automation">{{ trans('messages.source.launch') }}</a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    
    <iframe src="{{ action('Automation2Controller@edit', [
        'uid' => $automation->uid,
        'auto_popup' => true,
    ]) }}" id="trans_frame" class="trans_frame" style="display:none"></iframe>
    
    <script>
        var popup = new Popup();

        function jReload() {
            $.ajax({
                method: 'GET',
                url: '',
            })
            .done(function(repsonse) {
                $('.source-stats').html($('<div>').html(repsonse).find('.source-stats').html());
            });
        }
        
        $(document).on('click', '.launch-automation', function(e) {
            e.preventDefault();

            $('.trans_frame').fadeIn();
            
            $('html').css('overflow', 'hidden');
        });
    </script>
@endsection
