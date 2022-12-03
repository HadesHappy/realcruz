@extends('layouts.core.backend')

@section('title', trans('messages.form_templates'))

@section('page_header')

    <div class="page-title">                
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.form_templates') }}</span>
        </h1>
        <p style="margin-bottom:0">{!! trans('messages.form_templates.intro') !!}</p>
    </div>

@endsection

@section('content')
    <script>
        @include('forms.frontend.popupJs')
    </script>

    <div id="TemplatesIndexContainer" class="listing-form  view-{{ request()->view ? request()->view : 'grid' }}"
        data-url="{{ action('Admin\TemplateController@listing') }}"
        per-page="{{ Acelle\Model\Template::$itemsPerPage }}"                    
    >                
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="">                
                <div class="filter-box">
                    <input type="hidden" name="view" value="{{ request()->view }}" />
                    <input type="hidden" name="type" value="{{ isset($type) ? $type : '' }}" />
                    @if (request()->view && request()->view == 'list')
                        <div class="checkbox inline check_all_list">
                            <label>
                                <input type="checkbox" name="page_checked" class="styled check_all">
                            </label>
                        </div>
                        <div class="dropdown list_actions" style="display: none">
                            <button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" link-confirm="{{ trans('messages.delete_templates_confirm') }}" href="{{ action('Admin\TemplateController@delete') }}">
                                        <span class="material-symbols-rounded">
    delete_outline
    </span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    <span class="filter-group">
                        {{-- <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span> --}}
                        <select class="select" name="sort_order">
                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                            <option value="name">{{ trans('messages.name') }}</option>
                        </select>                                        
                        <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                            <span class="material-symbols-rounded desc">
sort
</span>
                        </button>
                    </span>
                    <span class="filter-group hide">
                        <select class="select" name="from">
                            <option value="all">{{ trans('messages.all') }}</option>
                        </select>                                        
                    </span>
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
search
</span>
                    </span>
                </div>
            </div>
        </div>
        
        <div id="TemplatesIndexContent" class="pml-table-container">
            
            
            
        </div>
    </div>
    
    <script>
        var TemplatesIndex = {
            list: null,
            getList: function() {
                if (this.list == null) {
                    this.list = makeList({
                        url: '{{ action('Admin\FormTemplateController@listing') }}',
                        container: $('#TemplatesIndexContainer'),
                        content: $('#TemplatesIndexContent')
                    });
                }
                return this.list;
            }
        };

        $(document).ready(function() {
            TemplatesIndex.getList().load();
        });
    </script>
@endsection
