@if (count($templates) > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}">
        @foreach ($templates as $key => $template)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $template['id'] }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td width="1%">
                    <a href="#preview">
                        <img class="template-thumb rounded shadow-sm" width="100" height=""
                            src="{{ $template['screenshot'][0] }}?v={{ rand(0,10) }}" />
                    </a>
                </td>
                <td width="70%">
                    <a class="kq_search fw-600 d-block list-title" href="#preview">
                        {{ $template['name'] }}
                    </a>
                    <span class="text-muted small">{{ $template['description'] }}</span>
                </td>

                <td class="text-end">
                    @if (!$template['active'])
                        <a link-method="POST" href="{{ action('Site\TemplateController@activate', $template['id']) }}"
                            role="button" class="btn btn-secondary list-action-single"
                        >
                            {{ trans('messages.site.template.activate') }}
                        </a>
                    @else
                        <span class="label label-flat bg-activated">{{ trans('messages.activated') }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    
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
