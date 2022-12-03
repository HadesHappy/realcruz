@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("FormController@index") }}">{{ trans('messages.forms') }}</a></li>
        </ul>
        <h1>
            <span class="material-symbols-rounded">
                add
                </span> {{ trans('messages.form.create') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <form id="FormCreate" action="{{ action('FormController@create') }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'name',
                    'value' => $form->name,
                    'label' => trans('messages.form.enter_name'),
                    'help_class' => 'template',
                    'rules' => ['name' => 'required']
                ])

                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'mail_list_uid',
                    'include_blank' => '--',
                    'label' => trans('messages.select_list'),
                    'value' => '',
                    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                    'rules' =>  ['mail_list_uid' => 'required']
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="position: relative;">
                <div class="d-flex align-items-center mt-4 template-create-sticky">
                    <h3 class="text-semibold mr-auto mb-0 mt-0">{{ trans('messages.template.select_your_template') }}</h3>
                    <div class="text-left">
                        <a href="javascript:;" class="btn btn-secondary me-2 start-design">
                            <i class="icon-check"></i> {{ trans('messages.template.create_and_design') }}
                        </a>
                    </div>
                </div>

                <div class="subsection pb-4">
                    <h2 class="font-weight-semibold mb-0">{{ trans('messages.form.template') }}</h2>
                    <hr>

                    <div id="form-templates" class="pb-4">
                        <div class="">				
                            <div class="d-flex top-list-controls">
                                <div class="col-md-9">
                                    <div class="filter-box">
                                        <span class="filter-group">
                                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                                            <select class="select" name="sort_order">
                                                <option value="id">{{ trans('messages.default') }}</option>
                                                <option value="name">{{ trans('messages.name') }}</option>
                                                <option value="created_at">{{ trans('messages.created_at') }}</option>
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
                                </div>
                            </div>
                            
                            <div id="form-templates-content">
                            </div>
                        </div>
                    </div>
                    <br style="clear:both" /><br style="clear:both" />
                </div>
            </div>
        </div>

    </form>

    <script>
        $(function() {
            var FormTemplatelist = makeList({
                url: '{{ action('FormController@templates') }}',
                container: $('#form-templates'),
                content: $('#form-templates-content')
            });

            FormTemplatelist.load();

            // submit
            $('.start-design').on('click', function() {
                var form = $('#FormCreate');
                console.log(form.find('[name=template_uid]:checked').length);
				
				if (form.find('[name=template_uid]:checked').length == 0) {
					// Success alert
					new Dialog('alert', {
						title: "{{ trans('messages.notify.error') }}",
						message: "{{ trans('messages.template.need_select_template') }}",
					});
					return;
				}
                
                form.submit();
            });
        });
    </script>
@endsection