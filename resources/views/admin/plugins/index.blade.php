@extends('layouts.core.backend')

@section('title', trans('messages.plugins'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">
                format_list_bulleted
                </span> {{ trans('messages.plugins') }}</span>
		</h1>
	</div>

@endsection

@section('content')
	<p>{{ trans('messages.plugin.wording') }}</p>

	<div class="listing-form"
		sort-url="{{ action('Admin\PluginController@sort') }}"
		data-url="{{ action('Admin\PluginController@listing') }}"
		per-page="{{ Acelle\Model\Plugin::$itemsPerPage }}"
	>
		<div class="d-flex top-list-controls top-sticky-content">
			<div class="me-auto">
				@if ($plugins->count() >= 0)
					<div class="filter-box">
						<div class="dropdown list_actions" style="display: none">
							<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
								{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" link-confirm="{{ trans('messages.enable_plugins_confirm') }}" href="{{ action('Admin\PluginController@enable') }}"><span class="material-symbols-rounded">
play_arrow
</span> {{ trans('messages.enable') }}</a></li>
								<li><a class="dropdown-item" link-confirm="{{ trans('messages.disable_plugins_confirm') }}" href="{{ action('Admin\PluginController@disable') }}"><span class="material-symbols-rounded">
hide_source
</span> {{ trans('messages.disable') }}</a></li>
								<li><a class="dropdown-item" link-confirm="{{ trans('messages.delete_plugins_confirm') }}" href="{{ action('Admin\PluginController@delete') }}"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
							</ul>
						</div>
						<span class="text-nowrap">
							<input type="text" name="keyword" class="form-control search" value="{{ request()->keyword }}" placeholder="{{ trans('messages.type_to_search') }}" />
							<span class="material-symbols-rounded">
search
</span>
						</span>
					</div>
				@endif
			</div>
			<div class="text-end">
				<a href="{{ action("Admin\PluginController@install") }}" role="button" class="btn btn-secondary">
					<span class="material-symbols-rounded">
add
</span> {{ trans('messages.install_plugin') }}
				</a>
			</div>
		</div>

		<div class="pml-table-container">
		</div>
	</div>

	<script>
        var PluginsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('Admin\PluginController@listing') }}',
                    container: $('.listing-form'),
                    content: $('.pml-table-container')
                });
            }
        };

        $(function() {
            PluginsIndex.getList().load();
        });
    </script>
@endsection
