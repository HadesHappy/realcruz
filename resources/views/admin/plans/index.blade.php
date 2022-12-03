@extends('layouts.core.backend')

@section('title', trans('messages.plans'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title" style="padding-bottom:0">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.plans') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-8"><p>{{ trans('messages.plan_create_message') }}</p></div>
    </div>

    <div class="listing-form"
        sort-url="{{ action('Admin\PlanController@sort') }}"
        data-url="{{ action('Admin\PlanController@listing') }}"
        per-page="{{ Acelle\Model\Plan::$itemsPerPage }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                @if ($plans->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="plans.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="plans.name">{{ trans('messages.name') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="asc" />
                                                <button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-symbols-rounded desc">
sort
</span>
                            </button>
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
            @can('create', new Acelle\Model\Plan())
                <div class="text-end">
                    <a href="{{ action("Admin\PlanController@wizard") }}" role="button" class="btn btn-secondary modal-action">
                        <span class="material-symbols-rounded">
add
</span> {{ trans('messages.create_plan') }}
                    </a>
                </div>
            @endcan
        </div>

        <div class="pml-table-container">
        </div>
    </div>

    <script>
        var PlanIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\PlanController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            PlanIndex.getList().load();
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.modal-action').click(function(e) {
                e.preventDefault();

                PlansIndex.getWizardPopup().load();
            });
        });

        var PlansIndex = {
            wizardPopup: null,

            getWizardPopup: function() {
                if (this.wizardPopup == null) {
                    this.wizardPopup = new Popup({
                        url: '{{ action("Admin\PlanController@wizard") }}',
                    });
                }
                return this.wizardPopup;
            }
        }
    </script>
@endsection
