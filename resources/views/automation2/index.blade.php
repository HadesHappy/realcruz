@extends('layouts.core.frontend')

@section('title', trans('messages.Automations'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.Automations') }}</span>
        </h1>				
    </div>
        
    @if(config('queue.default') == 'async')
        <div class="alert alert-warning">
            {{ trans('messages.automation_not_work_with_async') }}
        </div>
    @endif

@endsection

@section('content')
    <div id="Automation2IndexContainer" class="listing-form"
        data-url="{{ action('Automation2Controller@listing') }}"
        per-page="{{ \Acelle\Model\Automation2::ITEMS_PER_PAGE }}"					
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
                        <button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                    link-method="PATCH"
                                    link-confirm="{{ trans('messages.enable_automations_confirm') }}"
                                    href="{{ action('Automation2Controller@enable') }}"
                                >
                                    <span class="material-symbols-rounded me-2">
play_arrow
</span> {{ trans('messages.enable') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" link-method="PATCH" link-confirm="{{ trans('messages.disable_automations_confirm') }}" href="{{ action('Automation2Controller@disable') }}">
                                    <span class="material-symbols-rounded me-2">
hide_source
</span> {{ trans('messages.disable') }}</a></li>
                            <li>
                                <a class="dropdown-item" link-method='delete' link-confirm="{{ trans('messages.delete_automations_confirm') }}" href="{{ action('Automation2Controller@delete') }}">
                                <span class="material-symbols-rounded me-2">
delete_outline
</span> {{ trans('messages.delete') }}</a>
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
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
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
            </div>
            <div class="text-end">
                <a href="{{ action("Automation2Controller@wizard") }}" role="button" class="btn btn-secondary create-automation2">
                    <span class="material-symbols-rounded">
add
</span> {{ trans('messages.automation.create') }}
                </a>
            </div>
        </div>
        
        <div id="Automation2IndexContent">
            
            
            
        </div>
    </div>

    <script>
        var Automation2Index = {
            getList: function() {
                return makeList({
                    url: '{{ action('Automation2Controller@listing') }}',
                    container: $('#Automation2IndexContainer'),
                    content: $('#Automation2IndexContent')
                });
            }
        };

        $(document).ready(function() {
            Automation2Index.getList().load();
        });
    </script>
        
    <script>
        var createAutomationPopup;
    
        function showCreateCampaignPopup() {
            var url = '{{ action("Automation2Controller@wizardTrigger") }}';
            
            createAutomationPopup = new Popup();
            createAutomationPopup.load(url);            
        }
        
        $(document).ready(function() {
        
            $('.create-automation2').click(function(e) {
                e.preventDefault();
                
                showCreateCampaignPopup();
            });
        
        });
        
    </script>
@endsection
