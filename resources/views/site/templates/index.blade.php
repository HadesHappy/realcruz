@extends('layouts.core.frontend')

@section('title', trans('messages.templates'))

@section('page_header')

    <div class="page-title">                
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>

        <div class="d-flex align-items-center">
            <div>
                <h1 class="d-flex align-items-center">
                    <span class="material-symbols-rounded me-3">
                        format_list_bulleted
                        </span> <span class="text-semibold">{{ trans('messages.templates') }}</span>
                </h1> 
            </div>
        </div>
                           
    </div>

@endsection

@section('content')
    <div id="TemplatesIndexContainer" class="listing-form  view-{{ request()->view ? request()->view : 'list' }}"
        data-url="{{ action('TemplateController@listing') }}"
        per-page="{{ Acelle\Model\Template::$itemsPerPage }}"                    
    >                
        <div class="d-flex top-list-controls top-sticky-content">
            <div class="">                
                <div class="filter-box">
                    <input type="hidden" name="view" value="{{ request()->view }}" />
                    @if (!request()->view || request()->view == 'list')
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
                                    <a class="dropdown-item" link-confirm="{{ trans('messages.delete_templates_confirm') }}" href="{{ action('TemplateController@delete') }}">
                                        <span class="material-symbols-rounded">
    delete_outline
    </span> {{ trans('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    <span class="filter-group">
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
                    @if (request()->from != 'mine')
                        <span class="filter-group">
                            <select class="select" name="category_uid">
                                <option value="">{{ trans('messages.template.all_categories') }}</option>
                                @foreach (\Acelle\Model\TemplateCategory::all() as $category)
                                    <option value="{{ $category->uid }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </span>
                    @endif
                    <span class="filter-group">
                        <input type="hidden" name="from" value="{{ request()->from ? request()->from : 'mine' }}" />
                    </span>
                    <span class="text-nowrap">
                        <input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
                        <span class="material-symbols-rounded">
search
</span>
                    </span>
                </div>
            </div>
            <div class="text-end d-flex align-items-center ms-auto">
                <div class="view-toggle d-flex ml-auto">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ action('Site\TemplateController@index', [
                            'from' => request()->from ? request()->from : 'mine',
                            'view' => 'grid',
                        ]) }}"
                            class="btn btn-default view-toogle grid m-icon"
                        >
                            <span class="material-symbols-rounded">
                                grid_view
                            </span>
                        </a>
                        <a href="{{ action('Site\TemplateController@index', [
                            'from' => request()->from ? request()->from : 'mine',
                            'view' => 'list',
                        ]) }}"
                            class="btn btn-default view-toogle list m-icon mr-3"
                        >
                            <span class="material-symbols-rounded">
                                reorder
                            </span>
                        </a>
                    </div>
                </div>

                <div>
                    <a href="{{ action('TemplateController@uploadTemplate') }}" class="btn btn-light ml-auto">
                        <span class="material-symbols-rounded">
    file_upload
    </span> {{ trans('messages.upload') }}
                    </a>  
                    <a href="{{ action('TemplateController@builderCreate') }}" class="btn btn-secondary">
                        <span class="material-symbols-rounded">
    add
    </span> {{ trans('messages.create') }}
                    </a>
                                      
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
                        url: '{{ action('Site\TemplateController@listing') }}',
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
