@extends('layouts.popup.medium')

@section('content')
    <h3 class="mb-0 text-center">{{ trans('messages.campaign.preview_as.enter_search') }}</h3>
    <div class="listing-form"
        data-url="{{ action('CampaignController@subscribersListing', $campaign->uid) }}"
        per-page="{{ Acelle\Model\Subscriber::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content mt-0">
            <div class="me-auto" style="display: flex;justify-content: center;width: 100%;">        
                <div class="filter-box mb-3 mt-2">
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
            search
            </span>
                    </span>
                    <button type="button" class="btn btn-primary ms-2 px-4 search-ok-button">{{ trans('messages.ok') }}</button>
                </div>
            </div>
        </div>

        <div class="pml-table-container">


        </div>
    </div>

    <script>
        var SubscribersIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('CampaignController@previewAsList', $campaign->uid) }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            SubscribersIndex.getList().load();

            $('.search-ok-button').on('click', function() {
                SubscribersIndex.getList().load();
            });
        });
    </script>
@endsection