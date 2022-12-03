@extends('layouts.core.backend', [
	'body_class' => 'has-topfix-header',
])

@section('title', trans('messages.plugins'))

@section('page_header')

	<div class="topfix-header">
		<div class="topfix-container border-bottom d-flex align-items-center shadow-sm">
			<div style="width: 20%">
				<div class="d-flex align-items-center">
					<h4 class="mb-0 me-3">{{ trans('messages.plugins') }}</h4>
					<div class="text-muted2 small how-use hide">
						<a href="javascript:;" class="text-secondary">
							<span class="material-symbols-rounded">
								help
							</span>
							How to use
						</a>
					</div>
				</div>
			</div>
			
			<div class="topfix-search text-center d-flex justify-content-center" style="width: 60%">
				<div class="topfix-search-icon">
					<input type="text" name="keyword" class="topfix-search-input form-control"
						placeholder="{{ trans('messages.search_installed_plugins') }}" />
					<span class="topfix-search-icon-span material-symbols-rounded">search</span>
					<span class="topfix-close-icon-span material-symbols-rounded" style="display: none">close</span>
				</div>
			</div>
		</div>
	</div>

	<style>
		body {
			background: #efefef;
		}
	</style>

@endsection

@section('content')
	<div class="topfix-body px-4 py-4">
		
			<div class="d-flex align-items-center">
				<div>
					<h4 class="font-weight-semibold">{{ trans('messages.installed_plugins') }}</h4>
					<p class="mb-0">{{ trans('messages.plugin.wording') }}</p>
				</div>
				<div class="text-end ms-auto">
					<a href="{{ action("Admin\PluginController@install") }}" role="button" class="btn btn-secondary text-nowarp ms-3">
						<span class="material-symbols-rounded">
	add
	</span> {{ trans('messages.install_plugin') }}
					</a>
				</div>
			</div>

			<div class="listing-form"
				sort-url="{{ action('Admin\PluginController@sort') }}"
				data-url="{{ action('Admin\PluginController@listing') }}"
				per-page="{{ Acelle\Model\Plugin::$itemsPerPage }}"
			>
				<div class="d-flex top-list-controls top-sticky-scontent">
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
							</div>
						@endif
					</div>
					
				</div>
				
				<div class="pml-table-container">
				</div>

			</div>
			

		<script>
			var PluginsIndex = {
				list: null,
				getList: function() {
					if (this.list == null) {
						this.list = makeList({
							url: '{{ action('Admin\PluginController@listing') }}',
							container: $('.listing-form'),
							content: $('.pml-table-container'),
							data: function() {
								return {
									keyword: $('.topfix-search [name=keyword]').val()
								};
							}
						});
					}

					return this.list;
				},
				getSearchInput: function() {
					return $('.topfix-search [name=keyword]');
				},
				getSearchKeyword: function() {
					return this.getSearchInput().val();
				},
				toggleCloseIcon: function() {
					if (this.getSearchKeyword().trim() == '') {
						$('.topfix-close-icon-span').hide();
					} else {
						$('.topfix-close-icon-span').show();
					}
				},
				clearSearchKeyword: function() {
					this.getSearchInput().val('');
					this.getSearchInput().trigger('change');
					this.getSearchInput().focus();
				}
			};

			$(function() {
				PluginsIndex.getList().load();

				// keyword change
				$('.topfix-search [name=keyword]').on('keyup change', function() {
					PluginsIndex.getList().load();

					// 
					PluginsIndex.toggleCloseIcon();
				});

				$('.topfix-close-icon-span').on('click', function() {
					// 
					PluginsIndex.clearSearchKeyword();
				});
			});
		</script>
	</div>
@endsection
