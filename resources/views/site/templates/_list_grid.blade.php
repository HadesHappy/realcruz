@if (count($templates) > 0)
    <div class="row mt-4">
        @foreach ($templates as $key => $template)
            <div class="col-md-2 col-sm-6 mb-4">
                <div class="card mb-4 shadow-sm template-card">
                    <span class="template-image-box2">
                        <img class="card-img-top" src="{{ $template['screenshot'][0] }}" style="height: 100%; width: auto; display: block;">
                        <div class="preview_control">
                            <div style="width: calc(80% - 0px);">
                                <div>
                                    <div>
                                        <div>
                                            @if (!$template['active'])
                                                <a link-method="POST" href="{{ action('Site\TemplateController@activate', $template['id']) }}"
                                                    role="button" class="btn btn-primary list-action-single d-block"
                                                >
                                                    {{ trans('messages.site.template.activate') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                    <div class="card-body p-3">
                        <h6 title="{{ $template['name'] }}" class="fw-600 mt-1 mb-1 text-ellipsis">
                            {{ $template['name'] }}
                            @if ($template['active'])
                                <div style="line-height:25px">
                                    <span class="label label-flat bg-activated">{{ trans('messages.activated') }}</span>
                                </div>
                            @endif
                        </h6>
                        <p class="small text-muted">{{ $template['description'] }}</p>
                        
                        <div class="">
                            <div class="d-flex align-items-center justify-content-end">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
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
