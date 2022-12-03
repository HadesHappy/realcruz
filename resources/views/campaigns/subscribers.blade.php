@extends('layouts.core.frontend')

@section('title', $campaign->name)

@section('page_header')

    @include("campaigns._header")

@endsection

@section('content')

@include("campaigns._menu")

<h2 class="text-semibold text-primary">{{ trans('messages.subscribers') }}</h2>

<div class="row mb-5">
    <div class="col-md-3">
        <div class="bg-teal-400">
            <div class="bg-secondary p-3 shadow rounded-3 text-white text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ number_with_delimiter($campaign->readCache('ActiveSubscriberCount')) }} / {{ number_with_delimiter($campaign->readCache('SubscriberCount')) }}</h2>
                <div class="text-muted">{{ trans('messages.campaign.active_subscribers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-teal-400">
            <div class="bg-secondary p-3 shadow rounded-3 text-white text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($campaign->readCache('DeliveredRate')) }}</h2>
                <div class="text-muted">{{ number_with_delimiter($campaign->readCache('DeliveredCount')) }} {{ trans('messages.campaign.successfully_delivered') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-teal-400">
            <div class="bg-secondary p-3 shadow rounded-3 text-white text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($campaign->readCache('FailedDeliveredRate')) }}</h2>
                <div class="text-muted">{{ number_with_delimiter($campaign->readCache('FailedDeliveredCount')) }} {{ trans('messages.campaign.failed_delivery_attempt') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-teal-400">
            <div class="bg-secondary p-3 shadow rounded-3 text-white text-center">
                <h2 class="text-semibold mb-1 mt-0">{{ number_to_percentage($campaign->readCache('NotDeliveredRate')) }}</h2>
                <div class="text-muted">{{ number_with_delimiter($campaign->readCache('NotDeliveredCount')) }} {{ trans('messages.campaign.pending_delivery') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="listing-form"
        data-url="{{ action('CampaignController@subscribersListing', $campaign->uid) }}"
        per-page="{{ Acelle\Model\Subscriber::$itemsPerPage }}"
    >
    <div class="d-flex top-list-controls top-sticky-content">
        <div class="me-auto">
            @if ($subscribers->count() >= 0)
                
                <div class="filter-box">
                    <span class="me-2 d-flex">
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
                                    <a class="dropdown-item assign-values-button"
                                    href="{{ action('SubscriberController@assignValues', $list->uid) }}">
                                        <span class="material-symbols-rounded">
        control_point_duplicate
        </span> {{ trans('messages.subscriber.assign_values') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"  link-method="POST" link-confirm="{{ trans('messages.subscribe_subscribers_confirm') }}"
                                        href="{{ action('SubscriberController@subscribe', $list->uid) }}">
                                        <span class="material-symbols-rounded">
        mark_email_read
        </span> {{ trans('messages.subscribe') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        link-confirm="{{ trans('messages.unsubscribe_subscribers_confirm') }}"
                                        href="{{ action('SubscriberController@unsubscribe', $list->uid) }}">
                                        <span class="material-symbols-rounded">
        logout
        </span> {{ trans('messages.unsubscribe') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item list-action-single"
                                        link-method="POST"
                                        link-confirm="{{ trans('messages.subscribers.resend_confirmation_email.confirm') }}" href="{{ action('SubscriberController@resendConfirmationEmail', $list->uid) }}">
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
                                    <a class="dropdown-item list-action-single"
                                        link-confirm="{{ trans('messages.delete_subscribers_confirm') }}"
                                        href="{{ action('SubscriberController@delete', $list->uid) }}">
                                        <span class="material-symbols-rounded">
        delete_outline
        </span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ action('SubscriberController@bulkDelete', $list->uid) }}"
                                        class="dropdown-item bulk-delete">
                                        <span class="material-symbols-rounded">
        delete_outline
        </span> {{ trans('messages.subscriber.bulk_delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
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
                                                <input {{ ($field->required ? "checked='checked'" : "") }} type="checkbox" id="{{ $field->tag }}" name="columns[]" value="{{ $field->uid }}" class="styled">
                                                {{ $field->label }}
                                            </label>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="created_at" name="columns[]" value="created_at" class="styled">
                                        {{ trans('messages.created_at') }}
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="updated_at" name="columns[]" value="updated_at" class="styled">
                                        {{ trans('messages.updated_at') }}
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <span class="filter-group ml-20">
                        <span class="title text-semibold text-muted">{{ trans('messages.subscribers_who') }}</span>
                        <select class="select me-3" name="open">
                            <option value="">-- {{ trans('messages.open') }} --</option>
                            <option {{ (request()->open == 'yes' ? "selected" : '') }}  value="yes">{{ trans('messages.opened') }}</option>
                            <option {{ (request()->open == 'no' ? "selected" : '') }} value="no">{{ trans('messages.not_opened') }}</option>
                        </select>
                        <!--<span class="small-select2">
                            <select class="select" name="and_or">
                                <option value="and">{{ trans('messages.and') }}</option>
                                <option value="or">{{ trans('messages.or') }}</option>
                            </select>
                        </span>-->
                        <select class="select" name="click">
                            <option value="">-- {{ trans('messages.click') }} --</option>
                            <option value="clicked">{{ trans('messages.clicked') }}</option>
                            <option value="not_clicked">{{ trans('messages.not_clicked') }}</option>
                        </select>
                    </span>
                    <span class="filter-group mr-20">
                        <span class="title text-semibold text-muted">{{ trans('messages.tracking_status') }}</span>
                        <select class="select" name="tracking_status">
                            <option value="">-- {{ trans('messages.all') }} --</option>
                            <!--<option value="not_sent">{{ trans('messages.not_sent') }}</option>-->
                            <option value="error">{{ trans('messages.error') }}</option>
                            <option value="sent">{{ trans('messages.sent') }}</option>
                        </select>
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
    </div>

    <div class="pml-table-container">


    </div>
</div>

<script>
    var SubscribersIndex = {
        getList: function() {
            return makeList({
                url: '{{ action('CampaignController@subscribersListing', $campaign->uid) }}',
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
