@extends('layouts.core.frontend')

@section('title', trans('messages.create_template'))

@section('head')      
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("TemplateController@index") }}">{{ trans('messages.templates') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.template.new_template') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-6">
            <form action="{{ action('TemplateController@builderCreate') }}" method="POST" class="template-form form-validate-jquery">
                {{ csrf_field() }}
                
				<input type="hidden" value="" name="template" />
                
                <div class="sub_section">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => '',
                        'name' => 'name',
                        'value' => $template->name,
                        'label' => trans('messages.enter_template_name'),
                        'help_class' => 'template',
                        'rules' => ['name' => 'required'],
                    ])
                </div>
            </form>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12" style="position: relative;">
			<div class="d-flex align-items-center mt-4 template-create-sticky">
				<h3 class="text-semibold mr-auto mb-0 mt-0">{{ trans('messages.template.select_your_template') }}</h3>
				<div class="text-left">
					<button class="btn btn-secondary me-2 start-design"><i class="icon-check"></i> {{ trans('messages.template.create_and_design') }}</button>
				</div>
			</div>

			@foreach (Acelle\Model\TemplateCategory::all() as $category)
				@if ($category->templates()->count())
					<div class="subsection pb-4">
						<h2 class="font-weight-semibold mb-0">{{ $category->name }}</h2>
						<hr>

						<div id="gallery-{{ $category->id }}" class="pb-4">
							<div class="listing-form"
								data-url="{{ action('TemplateController@builderTemplates', [
									'category_uid' => $category->uid,
								]) }}"
								per-page="25"					
							>				
								<div class="d-flex top-list-controls top-sticky-contentx">
									<div class="col-md-9">
										<div class="filter-box">
											<span class="filter-group">
												<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
												<select class="select" name="sort_order">
													<option value="id">{{ trans('messages.default') }}</option>
													<option value="name">{{ trans('messages.name') }}</option>
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
								
								<div id="gallery-{{ $category->id }}-content" class="pml-table-container">
								</div>
							</div>
						</div>
						<br style="clear:both" /><br style="clear:both" />
					</div>

					<script>
						$(function() {
							var list = makeList({
								url: '{{ action('TemplateController@builderTemplates', [
									'category_uid' => $category->uid,
								]) }}',
								container: $('#gallery-{{ $category->id }}'),
								content: $('#gallery-{{ $category->id }}-content')
							});

							list.load();
						});
					</script>
				@endif
			@endforeach
        </div>
    </div>
    
        
    <script>	
        $(document).ready(function() {
            $(document).on('click', '.select-template-layout', function() {
				var template = $(this).attr('data-template');
                
                // unselect all layouts
                $('.select-template-layout').removeClass('selected');
                
                // select this
                $(this).addClass('selected');

				// unselect all
				$('[name=template]').val('');
				
				// update template value
				if (typeof(template) !== 'undefined') {
					$('[name=template]').val(template);
				}
            });
            
            $('.select-template-layout').eq(0).click();
            
            $(document).on('click', '.start-design', function() {
                var form = $('.template-form');
				
				if ($('.select-template-layout.selected').length == 0) {
					// Success alert
					new Dialog('alert', {
						title: "{{ trans('messages.notify.error') }}",
						message: "{{ trans('messages.template.need_select_template') }}",
					});
					return;
				}
                
                if (form.valid()) {
                    form.submit();
                }
            });
        });
    </script>
    
@endsection
