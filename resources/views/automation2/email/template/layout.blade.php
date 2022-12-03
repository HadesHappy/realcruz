@extends('layouts.popup.large')

@section('content')
	
	@include('automation2.email._tabs', ['tab' => 'template'])
		
	<div class="row">
        <div class="col-md-12 template-list-container">
            <h5 class="mb-3">{{ trans('messages.automation.choose_your_template_layout') }}</h5>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            @include('automation2.email.template._tabs')

			<h2 class="font-weight-semibold mb-3">
				@if (request()->category_uid)
					{{ Acelle\Model\TemplateCategory::findByUid(request()->category_uid)->name }}
				@elseif (request()->from == 'mine')
					{{ trans('messages.my_templates') }}
				@endif
			</h2>

			<div class="filter-box">
				<span class="d-flex align-items-center mr-4">
					<input type="hidden" name="sort_order" value="id" />	
					<input type="hidden" name="sort_direction" value="desc" />	
					<input type="hidden" name="category_uid" value="{{ request()->category_uid }}" />
					<input type="hidden" name="from" value="{{ request()->from }}" />
					<input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
					<span class="material-symbols-rounded">
search
</span>
				</span>
			</div>
			<div class="template-list ajax-list">
				
			</div>
        </div>
    </div>
        
    <script>
		var listTheme = makeList({
            url: '{{ action('Automation2Controller@templateLayoutList', [
				'uid' => $automation->uid,
				'email_uid' => $email->uid,
			]) }}',
            container: $('.template-list-container'),
            content: $('.template-list')
        });
		
		listTheme.load();
		
		// filters
		$('[name=from]').change(function() {
			listTheme.load();
		});
    </script>
@endsection