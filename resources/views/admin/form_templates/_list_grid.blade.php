@if ($templates->count() > 0)
    <div class="row mt-4">
        @foreach ($templates as $key => $template)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card mb-4 shadow-sm template-card">
                    <span class="template-image-box2">
                        <img class="card-img-top" src="{{ $template->getThumbUrl() }}" style="height: 100%; width: auto; display: block;">
                        <div class="preview_control">
                            <div style="width: calc(80% - 0px);">
                                <div class="mb-2">
                                    <a class="btn btn-light d-block form-popup-preview" href="{{ action('Admin\FormTemplateController@preview', $template->uid) }}" oxnclick="popupwindow('{{ action('Admin\TemplateController@preview', $template->uid) }}', `{{ $template->name }}`, 800, 320)"><span class="material-symbols-rounded">
                                        zoom_in
                                        </span> {{ trans("messages.preview") }}</a>
                                </div>
                                <div>
                                    <div>
                                        @if (Auth::user()->admin->can('update', $template))
                                            @if (in_array(Acelle\Model\Setting::get('builder'), ['both','pro']) && $template->builder)
                                                <div>
                                                    <a href="{{ action('Admin\TemplateController@builderEdit', $template->uid) }}" role="button" class="btn btn-primary btn-icon template-compose d-block mb-2">
                                                        {{ trans('messages.template.pro_builder') }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if (in_array(Acelle\Model\Setting::get('builder'), ['both','classic']))
                                                <div>
                                                    <a href="{{ action('Admin\TemplateController@edit', $template->uid) }}" role="button" class="btn btn-info btn-icon template-compose-classic d-block">
                                                        {{ trans('messages.template.classic_builder') }}
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                    <div class="card-body p-3 bg-light">
                        <h6 title="{{ $template->name }}" class="fw-600 mt-1 mb-1 text-ellipsis">{{ $template->name }}</h6>
                        <div class="">
                            <div class="d-flex align-items-center justify-content-end">
                                @if (Auth::user()->admin->can('preview', $template) ||
                                    Auth::user()->admin->can('copy', $template) ||
                                    Auth::user()->admin->can('delete', $template) ||
                                    Auth::user()->admin->can('update', $template))
                                    <div class="btn-group">
                                        <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                            {{ trans('messages.actions') }} </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (Auth::user()->admin->can('update', $template))
                                                <li>
                                                    <a class="dropdown-item change-template-name"
                                                        href="{{ action('Admin\TemplateController@changeName', [
                                                            'uid' => $template->uid,
                                                        ]) }}">
                                                        <span class="material-symbols-rounded">subtitles</span>
                                                        {{ trans("messages.template.change_name") }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (Auth::user()->admin->can('preview', $template))
                                                <li><a class="dropdown-item form-popup-preview" href="{{ action('Admin\FormTemplateController@preview', $template->uid) }}"><span class="material-symbols-rounded">
            zoom_in
            </span> {{ trans("messages.preview") }}</a></li>
                                            @endif
                                            @if (Auth::user()->admin->can('update', $template))
                                                <li>
                                                    <a class="dropdown-item upload-thumb-button" href="{{ action('Admin\TemplateController@updateThumb', $template->uid) }}">
                                                        <span class="material-symbols-rounded">
            insert_photo
            </span> {{ trans("messages.template.upload_thumbnail") }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (Auth::user()->admin->can('copy', $template))
                                                <li>
                                                    <a
                                                        href="{{ action('Admin\TemplateController@copy', $template->uid) }}"
                                                        role="button"
                                                        class="dropdown-item copy-template-button"
                                                        link-method="GET"
                                                    >
                                                        <span class="material-symbols-rounded me-2">
            copy_all
            </span> {{ trans("messages.template.copy") }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (Auth::user()->admin->can('delete', $template))
                                                <li><a
                                                    class="dropdown-item list-action-single"
                                                    link-confirm="{{ trans('messages.delete_templates_confirm') }}"
                                                    href="{{ action('Admin\TemplateController@delete', ["uids" => $template->uid]) }}">
                                                    <span class="material-symbols-rounded me-2">
            delete_outline
            </span> {{ trans("messages.delete") }}</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @include('elements/_per_page_select', ["items" => $templates, 'custom_per_pages' => [8, 16, 24]])

    <script>
        $(function() {
            $('.form-popup-preview').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var overlay = $(this).attr('data-overlay');

                popup = new AFormPopup({
                    url: url
                });

                popup.load();
            });
        });
    </script>
    
    <script>
        $(function() {
            // change name click
            $('.change-template-name').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                TemplatesList.getChangeNamePopup().load({
                    url: url
                });
            });

            $('.copy-template-button').on('click', function(e) {
                e.preventDefault();			
                var url = $(this).attr('href');

                TemplatesList.getCopyPopup().load({
                    url: url
                });
            });

            $('.template-compose').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilder(url);
            });
            
            $('.template-compose-classic').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilderClassic(url);
            });
        });
            

        var TemplatesList = {
			copyPopup: null,
            changeNamePopup: null,

			getCopyPopup: function() {
				if (this.copyPopup === null) {
					this.copyPopup = new Popup();
				}

				return this.copyPopup;
			},
            
            getChangeNamePopup: function() {
				if (this.changeNamePopup === null) {
					this.changeNamePopup = new Popup();
				}

				return this.changeNamePopup;
			}
		}
    </script>

    <script>
		var thumbPopup = new Popup();    
        var categoriesPopup = new Popup();           
    
        $('.upload-thumb-button').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
            
            thumbPopup.load(url);
        });

        $('.template-categories').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
            
            categoriesPopup.load(url);
        });
    </script>

@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.template_empty_line_1') }}
        </span>
    </div>
@endif
