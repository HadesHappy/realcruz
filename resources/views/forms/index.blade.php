@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.forms') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div id="FormsIndexContainer" class="listing-form top-sticky"
        data-url="{{ action('FormController@list') }}"
    >
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="me-auto">
                <div class="filter-box">
                    <div class="checkbox inline check_all_list">
                        <label>
                            <input type="checkbox" name="page_checked" class="styled check_all">
                        </label>
                    </div>
                    <div class="dropdown list_actions" style="display: none">
                        <button type="button"
                            id="dropdownListActions"
                            class="btn btn-secondary dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownListActions">
                            <li>
                                <a class="action dropdown-item"
                                link-method="POST"
                                link-confirm="{{ trans('messages.forms.publish.confirm') }}"
                                href="{{ action('FormController@publish') }}">
                                    <span class="material-symbols-rounded me-2">task_alt</span>
                                    {{ trans('messages.form.publish') }}</a>
                            </li>
                            <li>
                                <a class="action dropdown-item"
                                link-method="POST"
                                link-confirm="{{ trans('messages.forms.unpublish.confirm') }}"
                                href="{{ action('FormController@unpublish') }}">
                                    <span class="material-symbols-rounded me-2">do_disturb_on</span>
                                    {{ trans('messages.form.unpublish') }}</a>
                            </li>
                            <li>
                                <a class="action dropdown-item"
                                link-method="POST"
                                link-confirm="{{ trans('messages.forms.delete.confirm') }}"
                                href="{{ action('FormController@delete') }}">
                                    <span class="material-symbols-rounded me-2">delete_outline</span>
                                    {{ trans('messages.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                        <select class="select" name="sort_order">
                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                            <option value="name">{{ trans('messages.name') }}</option>
                        </select>
                        <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                            <span class="material-symbols-rounded desc">
sort
</span>
                        </button>
                    </span>
                    <span class="filter-group d-flex align-items-center form-group-mb-0">
                        <span class="title text-semibold text-muted">{{ trans('messages.form.from_list') }}</span>
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'mail_list_uid',
                            'include_blank' => '-- ' . trans('messages.form.all_lists') . '--',
                            'label' => '',
                            'value' => '',
                            'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                            'rules' => [],
                        ])
                    </span>
                    <span class="filter-group d-flex align-items-center form-group-mb-0">
                        <span class="title text-semibold text-muted">{{ trans('messages.form.from_site') }}</span>
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'website_uid',
                            'include_blank' => '-- ' . trans('messages.form.all_sites') . '--',
                            'label' => '',
                            'value' => '',
                            'options' => Auth::user()->customer->getConnectedWebsiteSelectOptions(false),
                            'rules' => [],
                        ])
                    </span>
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
search
</span>
                    </span>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ action('FormController@create') }}" role="button" class="btn btn-secondary">
                    <span class="material-symbols-rounded">
add
</span> {{ trans('messages.form.create') }}
                </a>
            </div>
        </div>

        <div id="FormsIndexContent" class="pml-table-container">



        </div>
    </div>

    <script>
        @include('forms.frontend.popupJs')
    </script>

    <script>
        var FormsIndex = {
            list: null,
            getList: function() {
                if (this.list == null) {
                    this.list = makeList({
                        url: '{{ action('FormController@list') }}',
                        container: $('#FormsIndexContainer'),
                        content: $('#FormsIndexContent')
                    });
                }
                return this.list;
            }
        };

        $(document).ready(function() {
            FormsIndex.getList().load();
        });
    </script>
@endsection
